@props(['id', 'label', 'number', 'field', 'readOnly'])
<div @class(['form-control py-0', 'is-invalid' => has_form_error($field)])>
    <label for="{{$id}}">{{$label}}</label>
    {{-- UgaEats: the theme bundles intl-tel-input 18.3.3 but its JS loads the
         11.0.4 validation utils, so every number was reported "Invalid number"
         and an empty phone reached the server. data-* attributes override the
         picker options: load the matching utils and default to Uganda so
         "0772 123456" is understood as +256 772 123456. --}}
    <input
        data-control="country-code-picker"
        data-utils-script="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.3.3/js/utils.js"
        data-initial-country="ug"
        data-preferred-countries='["ug","ke","tz","rw"]'
        data-hidden-input-id="hidden-{{$id}}"
        data-container-class="w-100"
        id="{{$id}}"
        type="text"
        @class(['form-control shadow-none border-none'])
        aria-describedby="{{$id}}Feedback"
        value="{{$number}}"
        required
    />
</div>
<input
    type="hidden"
    id="hidden-{{$id}}"
    value="{{$number}}"
/>
<x-igniter-orange::forms.error field="{{$field}}" class="text-danger"/>
@script
<script>
    document.addEventListener('telephoneChange', (event) => {
        if (event.target.matches('#hidden-{{$id}}')) {
            $wire.$set('{{ $field }}', event.target.value, false);
        }
    });
</script>
@endscript
