

curl --request POST \
  --url https://api-free.deepl.com/v2/translate \
  --header 'Authorization: DeepL-Auth-Key 9e00d743-da37-8466-8e8d-18940eeeaf88:fx' \
  --header 'Content-Type: application/json' \
  --data '
{
  "text": [
    "Hello, World!"
  ],
  "target_lang": "FR",
  "source_lang": "EN",
  "context": "This is context.",
  "show_billed_characters": true,
  "split_sentences": "1",
  "preserve_formatting": false,
  "formality": "default",
  "model_type": "quality_optimized",
  "glossary_id": "def3a26b-3e84-45b3-84ae-0c0aaf3525f7",
  "style_id": "7ff9bfd6-cd85-4190-8503-d6215a321519",
  "custom_instructions": [
    "Use a friendly, diplomatic tone"
  ],
  "tag_handling": "html",
  "tag_handling_version": "v1",
  "outline_detection": true,
  "enable_beta_languages": false,
  "non_splitting_tags": [
    "non_splitting_tag"
  ],
  "splitting_tags": [
    "splitting_tag"
  ],
  "ignore_tags": [
    "ignore_tag"
  ]
}
'
