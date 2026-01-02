@props(['align' => 'right', 'width' => '48'])

@php
$alignmentClasses = $align === 'left' ? '' : 'dropdown-menu-end';
@endphp

<div class="dropdown">
    {{ $trigger }}

    <div class="dropdown-menu {{ $alignmentClasses }}">
        {{ $content }}
    </div>
</div>
