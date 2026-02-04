#! /bin/bash

curl --request POST \
  --url https://api-free.deepl.com/v2/translate \
  --header "Authorization: DeepL-Auth-Key " \
  --header "Content-Type: application/json" \
  --data '
{
  "text": [
    "/"
  ],
  "target_lang": "FR",
  "source_lang": "EN",
  "context": "",
  "show_billed_characters": true,
  "split_sentences": "1",
  "preserve_formatting": true,
  "formality": "default",
  
  "tag_handling": "html",
  "tag_handling_version": "v1",
  "non_splitting_tags": [
  ],
  "splitting_tags": [
  ],
  "ignore_tags": [
  ]
}
' > _datas/sh/result.txt
