# Release Notes

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