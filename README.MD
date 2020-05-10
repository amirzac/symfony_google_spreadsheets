### Set up:
* put json file with credentials into the project. Recommended directory is `config/google-api-client/` 
* set credentials file path to `GOOGLE_API_CREDENTIAL_FILE` value in .env
* set `GOOGLE_API_APPLICATION_NAME` value (optional)

### Commands:
* `php bin/console spreadsheets:get-and-save SheetID`
* `php bin/console spreadsheets:update SheetID`
---
> Data is stored in data.json file