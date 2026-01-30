#! /bin/bash

curl --request POST \
  --url https://api-free.deepl.com/v2/translate \
  --header 'Authorization: DeepL-Auth-Key 9e00d743-da37-8466-8e8d-18940eeeaf88:fx' \
  --header 'Content-Type: application/json' \
  --data '
{
  "text": [
    "$1"
  ],
  "target_lang": "EN",
  "source_lang": "FR",
  "context": "",
  "show_billed_characters": true,
  "split_sentences": "1",
  "preserve_formatting": false,
  "formality": "default",
  "custom_instructions": [
    "Use a friendly, diplomatic tone"
  ],
  "tag_handling": "html",
  "tag_handling_version": "v1",
  "non_splitting_tags": [
  ],
  "splitting_tags": [
  ],
  "ignore_tags": [
  ]
}
'
