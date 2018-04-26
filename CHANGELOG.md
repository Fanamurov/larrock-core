# Release Notes

## v1.6.8 (2018-04-26)
### Bugfix
- Trait AdminMethodsCreate: save with category

## v1.6.4 (2018-04-09)
### Added
- MessageLarrockEvent

## v1.6.1 (2018-04-06)
### Bugfix
- AdminMedtodsCreate. Use AdminMedtodsStore in anonymous function

## v1.6 (2018-04-06)
### Changed
- Component shareConfig method. Now config component in var "package" (old "app")
- AdminMethodsUpdate change code to add default value row
- AdminMethodsStore change code to add default value row
- replace "app" var to "package" in admin templates

## v1.5.3 (2018-04-06)
### Changed
- AdminMethodsCreate trait refactor

## v1.5.2 (2018-04-06)
### Bugfix
- js show easy category creating item

## v1.5.1 (2018-04-06)
### Changed
- AdminMethodsCreate trait refactor
- AdminMethodsStore trait refactor

## v1.5 (2018-04-05)
### Changed
- Component getValid method
- bugfixes
- PluginSeoTrait valid rules
- AdminMethodsEdit new validation implementation
- AdminMethodsStore new validation implementation
- AdminMethodsUpdate new validation implementation

### Removed
- Component valid_construct static method

## v1.4.18 (2018-04-05)
### Changed
- refactoring artisan commands: larrock:check, larrock:updateEnv, larrock:install
- bugfixes

## v1.4.16 (2018-04-04)
### Changed
- Breadcrumbs on edit page in admin replaced on admin.edit (routes.php)

### Bugfix
- FormCategory whereConnect

## v1.4.15 (2018-04-04)
### Added
- Component method overrideRow
- Component method removeRow

### Changed
- refactoring
- trait ShareMethods now return array

## v1.4.14 (2018-04-03)
### Added
- add blocksBig image photoGallery template

### Changed
- Refactoring AdminMenu
- Refactoring AdminMenuBuilder

## v1.4.12 (2018-04-03)
### Added
- FBElement method setModileAdminVisible

### Changed
- create_seo_table migration (seo_type_connect nullable)

### Bugfix
- ComponentPlugin method attachSeo

## v1.4.8 - 1.4.11 (2018-04-02)
### Changed
- bugfixes

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