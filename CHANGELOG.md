# Release Notes

## v1.4.7 (2018-03-30)
### Changed
- update menu admin template

## v1.4.6 (2018-03-30)
### Added
- js for new admin search module

### Removed
- Middleware SiteSearchAdmin removed in combineAdminMiddlewares

## v1.4.5 (2018-03-29)
### Changed
- AdminMethodEdit trait: added ability to edit material even if its section no longer exists (it is broken)

## v1.4.4 (2018-03-29)
### Added
- DB indexes for link table

### Changed
- DB indexes for link table
- migration config, seo, link

## v1.4.3 (2018-03-29)
### Changed
- ComponentPlugin method attachRows: write is not array and object data

## v1.4 (2018-03-28)
### Changed
- spatie/laravel-medialibrary v7
- directory storage files

### Removed
- Helpers/MediaFilesystem
- Helpers/MediaUrlGenerator

## v1.3.8 (2018-03-28)
### Added
- FormFile Formbuilder item

## v1.3.7.2 (2018-03-27)
### Bugfix
- larrock:updateEnv

## v1.3.7.1 (2018-03-27)
### Added
- Middleware ContactCreateTemplate add to default combineFrontMiddlewares

## v1.3.7 (2018-03-27)
### Added
- new Component method setRow()
- new env vars: MAIL_TEMPLATE_ADDRESS, MAIL_TEMPLATE_PHONE, MAIL_TEMPLATE_MAIL

## v1.3.6.5 (2018-03-27)
### Bugfix
- larrock:manager command

## v1.3.6.4 (2018-03-26)
### Added
- new FormBuilder element: FormButton

## v1.3.6.4 (2018-03-26)
### Added
- new FormBuilder element: FormButton

## v1.3.6 (2018-03-25)
### Added
- new properties in FBElement: $FBTemplate, $data 
- new methods in FBElement: setData, setFBTemplate
- new method toString() (render() replacement)

### Changed
- FormInput, FormHidden, Form... (childs FBElement) refactored
- Refactor tabbable() method in Component.php

### Removed
- render() method in FBElement

## v1.3.5.5 (2018-03-24)
### Changed
- optimized larrock:manager command
- optimized larrock:installcorepackages command

## v1.3.5.4 (2018-03-23)

### Changed
- bugfix ComponentPlugin attachSeo method

## v1.3.5.3 (2018-03-23)

### Added
- add CHANGELOG.md
- add configVendor/database.php

### Changed
- command larrock:install. New option --fast
- command larrock:updateVendorConfig