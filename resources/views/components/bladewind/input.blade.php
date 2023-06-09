@props([
    // name of the input field for use in forms
    'name' => 'input-'.uniqid(),
    // what type of input box are you displaying
    // availalble options are text, password, email, search, tel
    'type' => 'text',
    // label to display on the input box
    'label' => '',
    // should the input accept numbers only. Default is false
    'numeric' => false,
    // is this a required field. Default is false
    'required' => false,
    // adds margin after the input box
    'add_clearing' => true,
    'addClearing' => true,
    // placeholder text
    'placeholder' => '',
    // value to set when in edit mode or if you want to load the input with default text
    'selected_value' => '',
    'selectedValue' => '',
    // should the placeholder always be visible even if a label is set
    // by default the label overwrites the placeholder
    // useful if you dont want this overwriting
    'show_placeholder_always' => false,
    'showPlaceholderAlways' => false,
    // message to display when validation fails for this field
    // this is just attached to the input field as a data attribute
    'error_message' => '',
    'errorMessage' => '',
    // this is an easy way to pass a translatable heading to the notification component
    // since it is triggered from Javascript, it is hard to translate any text from within js
    'error_heading' => 'Error',
    'errorHeading' => 'Error',
    // how should error messages be displayed for this input
    // by default error messages are displayed in the Bladewind notification component
    // the component should exist on the page
    'show_error_inline' => false,
    'showErrorInline' => false,
    // for numeric input only: should the numbers include dots
    'with_dots' => true,
    'withDots' => true,

    'has_label' => false,
    'hasLabel' => false,
    'is_datepicker' => false,
    'isDatepicker' => false,
])
@php
    // reset variables for Laravel 8 support
    $add_clearing = filter_var($add_clearing, FILTER_VALIDATE_BOOLEAN);
    $addClearing = filter_var($addClearing, FILTER_VALIDATE_BOOLEAN);
    $show_placeholder_always = filter_var($show_placeholder_always, FILTER_VALIDATE_BOOLEAN);
    $showPlaceholderAlways = filter_var($showPlaceholderAlways, FILTER_VALIDATE_BOOLEAN);
    $show_error_inline = filter_var($show_error_inline, FILTER_VALIDATE_BOOLEAN);
    $showErrorInline = filter_var($showErrorInline, FILTER_VALIDATE_BOOLEAN);
    $with_dots = filter_var($with_dots, FILTER_VALIDATE_BOOLEAN);
    $withDots = filter_var($withDots, FILTER_VALIDATE_BOOLEAN);
    $has_label = filter_var($has_label, FILTER_VALIDATE_BOOLEAN);
    $hasLabel = filter_var($hasLabel, FILTER_VALIDATE_BOOLEAN);
    $is_datepicker = filter_var($is_datepicker, FILTER_VALIDATE_BOOLEAN);
    $isDatepicker = filter_var($isDatepicker, FILTER_VALIDATE_BOOLEAN);
    $required = filter_var($required, FILTER_VALIDATE_BOOLEAN);
    $numeric = filter_var($numeric, FILTER_VALIDATE_BOOLEAN);

    if (!$addClearing) $add_clearing = $addClearing;
    if ($showPlaceholderAlways) $show_placeholder_always = $showPlaceholderAlways;
    if ($showErrorInline) $show_error_inline = $showErrorInline;
    if (!$withDots) $with_dots = $withDots;
    if ($isDatepicker) $is_datepicker = $isDatepicker;

    if ($selectedValue !== $selected_value) $selected_value = $selectedValue;
    if ($errorMessage !== $error_message) $error_message = $errorMessage;
    if ($errorHeading !== $error_heading) $error_heading = $errorHeading;
    //--------------------------------------------------------------------

    $name = preg_replace('/[\s-]/', '_', $name);
    $required_symbol = ($label == '' && $required) ? ' *' : '';
    $is_required = ($required) ? 'required' : '';
    $placeholder_color = ($show_placeholder_always || $label == '') ? '' : 'placeholder-transparent';
    $placeholder_label = ($show_placeholder_always) ? $placeholder : (($label !== '') ? $label : $placeholder);
    $with_dots = ($with_dots) ? 1 : 0;
@endphp

<div class="relative w-full @if ($add_clearing) mb-3 @endif">
    <input
        {{ $attributes->merge(['class' => "bw-input w-full text-slate-600 border border-slate-300/50 dark:text-white dark:border-slate-700 dark:bg-slate-600 dark:focus:border-slate-900 peer $is_required $name $placeholder_color"]) }}
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ $selected_value }}"
        autocomplete="off"
        placeholder="{{ $placeholder_label }}{{ $required_symbol }}"
        @if ($numeric) onkeypress="return isNumberKey(event, {{ $with_dots }})" @endif
        @if ($error_message != '')
            data-error-message="{{ $error_message }}"
            data-error-inline="{{ $show_error_inline }}"
            data-error-heading="{{ $error_heading }}"
        @endif
    />
    @if ($error_message != '')<div class="text-red-500 text-xs p-1 {{ $name }}-inline-error hidden">{{ $error_message }}</div>@endif
    @if ($label !== '')
        <label for="{{ $name }}" class="form-label bg-white text-blue-900/40 " onclick="dom_el('.{{ $name }}').focus()">{!! $label !!}
            @if ($required)
            <span class="text-red-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-2 w-2 inline-block mt-[-2px]" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
            </span>
            @endif
        </label>
    @endif
</div>
<input type="hidden" class="bw-raw-select" />
