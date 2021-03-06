Changelog
=========
## v0.8.0
- [!!!][Task] Added compatibility for version 7.6.0 - 8.99.99 of TYPO3 CMS
- [!!!][Task] Refactored namespace from "Phoenix" to "FireLizard"
- [Task] Made composer ready 

## v0.7.0
- [Feature] Extended subscription to type 'data' and 'events'.
- [Feature] Trigger notification of subscribers on submitted form and clicked pins.

## v0.6.3
- [Bugfix] Catched 500-error if class defined by "filterProviderClass" (flexform) was not found.

## v0.6.2
- [Improvement] map instance is now accessible by DOM element set as canvas

## v0.6.1
- [!!!] Breaking Changes: Since the provider of maptiles now wants an API-Key the map has no images.
- [Hotfix] Changed provider of maptiles
- [Improvement] Also notify subscribers on first data request
- [Bugfix] Fixed JavaScript error when latitude and longitude were empty

## v0.6.0
- Use of editable Storage PID
- Use of DataType/QueryClassname instead of XCLass
- Split Providerinterface into interfaces for filter specific data and payload data 
- Subscription for additional data access per JavaScript
- Some refactorings

## v0.5.2
- Bugfix: Check uid before getting flexform from database.

## v0.5.1
- Set default filterobject to null to avoid server error.

## v0.5.0
- Status-Code 404 if settings not available on showAction
- Load animation on ajax request

## v0.4.3
- Don't require filterquery 
- Reformat code

## v0.4.2
- Don't use an icon if not provided

## v0.4.1
- Check if certain properties exists in coords response
- Disable cache for AJAX-calls

## v0.4.0
- Add pin icon support provided by a data provider.

## v0.3.0
- Add filter provided by a data provider.
- Bounds markers on leaflet map.

## v0.2.0
- Show one map provided by leaflet.
- Show markers with popups by a dataProvider.

## v0.1.0
- *pre-alpha state*
