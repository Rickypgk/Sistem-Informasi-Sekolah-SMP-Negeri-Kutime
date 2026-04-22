


<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name', 'type' => 'text', 'value' => '', 'placeholder' => '', 'required' => false, 'maxlength' => null]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['name', 'type' => 'text', 'value' => '', 'placeholder' => '', 'required' => false, 'maxlength' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<input
    type="<?php echo e($type); ?>"
    name="<?php echo e($name); ?>"
    id="<?php echo e($name); ?>"
    value="<?php echo e(old($name, $value)); ?>"
    placeholder="<?php echo e($placeholder); ?>"
    <?php echo e($required ? 'required' : ''); ?>

    <?php echo e($maxlength ? "maxlength=$maxlength" : ''); ?>

    <?php echo e($attributes->class([
        'w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400',
        'border-red-400' => $errors->has($name),
        'border-slate-300' => !$errors->has($name),
    ])); ?>

><?php /**PATH C:\PA 3\smpn-kutime\resources\views/components/form-input.blade.php ENDPATH**/ ?>