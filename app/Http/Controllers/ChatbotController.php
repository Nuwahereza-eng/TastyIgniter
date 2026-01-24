<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    /**
     * Handle chatbot message
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessage = $request->input('message');
        
        try {
            $response = $this->callGeminiAPI($userMessage);
            
            return response()->json([
                'success' => true,
                'response' => $response,
            ]);
        } catch (\Exception $e) {
            Log::error('Chatbot error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'response' => 'Sorry, I\'m having trouble right now. Please try again or contact us directly.',
            ], 500);
        }
    }

    /**
     * Call Google Gemini API
     */
    private function callGeminiAPI(string $message): string
    {
        $apiKey = config('services.gemini.api_key');
        
        if (empty($apiKey)) {
            return $this->getFallbackResponse($message);
        }

        $systemPrompt = $this->getSystemPrompt();
        
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $systemPrompt . "\n\nCustomer: " . $message]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'topK' => 40,
                'topP' => 0.95,
                'maxOutputTokens' => 500,
            ],
            'safetySettings' => [
                ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
            ],
        ]);

        if ($response->successful()) {
            $data = $response->json();
            
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                return $data['candidates'][0]['content']['parts'][0]['text'];
            }
        }

        Log::error('Gemini API error: ' . $response->body());
        return $this->getFallbackResponse($message);
    }

    /**
     * Get system prompt for the chatbot
     */
    private function getSystemPrompt(): string
    {
        return <<<PROMPT
You are UgaEats AI Assistant, a friendly and helpful customer support chatbot for UgaEats - Uganda's premier food ordering and delivery platform.

About UgaEats:
- We connect customers with local restaurants across Uganda
- We offer delivery and pickup options
- Operating hours vary by restaurant
- We accept mobile money (MTN, Airtel), cards, and cash on delivery
- Delivery typically takes 30-60 minutes depending on location

Your responsibilities:
1. Answer questions about ordering food, menu items, delivery
2. Help with order tracking inquiries
3. Explain how the platform works
4. Handle complaints politely and professionally
5. Suggest popular dishes when asked for recommendations

Guidelines:
- Be warm, friendly, and use Ugandan expressions occasionally (like "Webale!" for thank you)
- Keep responses concise (2-3 sentences max unless more detail is needed)
- If you don't know something specific about an order, direct them to check their email or call support
- Always be helpful and solution-oriented
- For order issues, apologize and assure them we'll help resolve it
- Never make up specific order details or restaurant information

Support contact: support@ugaeats.com or call +256 700 000 000
PROMPT;
    }

    /**
     * Fallback responses when API is unavailable
     */
    private function getFallbackResponse(string $message): string
    {
        $message = strtolower($message);
        
        $responses = [
            'hello' => "Hello! 👋 Welcome to UgaEats! How can I help you today?",
            'hi' => "Hi there! 👋 Welcome to UgaEats! What can I help you with?",
            'hey' => "Hey! 👋 Great to have you here! How can I assist you?",
            'order' => "To place an order:\n1. Search for a restaurant or dish\n2. Add items to your cart\n3. Proceed to checkout\n4. Choose delivery or pickup\n5. Pay and track your order!",
            'track' => "To track your order, go to 'My Account' → 'Recent Orders' and click on your order to see real-time status updates.",
            'delivery' => "Our delivery typically takes 30-60 minutes depending on your location and the restaurant. You'll receive updates via SMS and email.",
            'payment' => "We accept:\n• Mobile Money (MTN & Airtel)\n• Visa/Mastercard\n• Cash on Delivery",
            'contact' => "You can reach us at:\n📧 support@ugaeats.com\n📞 +256 700 000 000",
            'help' => "I can help you with:\n• Placing orders\n• Tracking deliveries\n• Payment questions\n• Restaurant info\n• Complaints & feedback\n\nWhat do you need help with?",
            'menu' => "You can browse our menu by visiting the 'Menu' section or searching for specific dishes. We have a variety of restaurants offering local Ugandan cuisine and international dishes!",
            'complaint' => "I'm sorry to hear you're having an issue. Please email support@ugaeats.com with your order number and we'll resolve it as quickly as possible. Webale for your patience!",
            'thank' => "You're welcome! 😊 Webale nyo! Is there anything else I can help you with?",
        ];

        foreach ($responses as $keyword => $response) {
            if (str_contains($message, $keyword)) {
                return $response;
            }
        }

        return "Thanks for your message! 😊 I'm here to help with orders, deliveries, payments, or any questions about UgaEats. What would you like to know?";
    }
}
