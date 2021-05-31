'use strict';
module.exports = {
  'extends': 'plugin:vue/recommended',
  'rules': {
    'vue/component-name-in-template-casing': ['error', 'PascalCase', {
      'ignores': [
        'component',
        'router-link',
        'router-view',
        'slot',
        'transition',
        'transition-group',
        'keep-alive'
      ]
    }],
    'vue/html-closing-bracket-newline': ['error', {
      'singleline': 'never',
      'multiline': 'never'
    }],
    'vue/html-closing-bracket-spacing': ['error', {
      'startTag': 'never',
      'endTag': 'never',
      'selfClosingTag': 'never',
    }],
    'vue/html-self-closing': ['error', {
      'html': {
        "void": "always",
        "normal": "never",
        "component": "any"
      }
    }],
    'vue/max-attributes-per-line': ['error', {
      'singleline': 1,
      'multiline': {
        'max': 1,
        'allowFirstLine': true
      }
    }],
    'vue/no-v-html': 'off',
    'vue/script-indent': ['error', 2, {
      'baseIndent': 1,
      'switchCase': 1,
    }]
  }
};