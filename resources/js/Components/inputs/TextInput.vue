<script setup>

import { Input } from 'postcss';
import InputText from 'primevue/inputtext';

defineProps({
  type: {
    type: String,
    default: 'text'
  },
  name: String,
  placeholder: String,
  required: {
    type: Boolean,
    default: false
  },
  class: {
    type: String,
    default: ''
  },
  readOnly: {
    type: Boolean,
    default: false
  },
  modelValue: String || Number,
})

const emit = defineEmits(['update:modelValue', 'keydown'])

const onValueChange = (e) => {
  emit('update:modelValue', e.target.value);
};

const onKeydown = (e) => {

  emit('keydown', e);
}

</script>

<template>
  <InputText
    :readonly="readOnly"
    :type="type"
    :id="name"
    :name="name"
    :class="class"
    class="bg-zinc-100 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-blue-500 focus:ring-4 focus:outline-none focus:border-blue-500 block w-full py-3.5 px-2.5 dark:bg-zinc-600 dark:border-zinc-500 dark:placeholder-zinc-400 dark:text-white gold:focus:ring-gold-500 gold:focus:border-gold-500 teal:focus:ring-teal-500 teal:focus:border-teal-500"
    :placeholder="placeholder"
    :required="required"
    @input="onValueChange"
    :value="modelValue"
    @keydown="onKeydown" />
</template>
