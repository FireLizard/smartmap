## 0.6.3
- [Bugfix] Catched 500-error if class defined by "filterProviderClass" (flexform) was not found.

## 0.6.2
- [Improvement] map instance is now accessible by DOM element set as canvas

## 0.6.1
- [!!!] Breaking Changes: Since the provider of maptiles now wants an API-Key the map has no images.
- [Hotfix] Changed provider of maptiles
- [Improvement] Also notify subscribers on first data request
- [Bugfix] Fixed JavaScript error when latitude and longitude were empty

## 0.6.0
- Use of editable Storage PID
- Use of DataType/QueryClassname instead of XCLass
- Split Providerinterface into interfaces for filter specific data and payload data 
- Subscription for additional data access per JavaScript
- Some refactorings

## 0.5.2
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
