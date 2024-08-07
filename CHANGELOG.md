# Changelog
## main

## v0.8.5
- Added action query to settings repository

## v0.8.4
- Added a fallback to empty responses on first and find in fake context

## v0.8.3
- Added search criteria factory

## v0.8.2
- Added search criteria repository

## v0.8.1
- Added a field repository fake

## v.0.8.0
- Added the parameter helper to all repositories

## v0.7.7
- Fixed typo of addressids

## v0.7.6
- Added address creation to the address repository

## v0.7.5
- Added the activity log repository

## v0.7.4
- Added imprint to the settings repository

## v0.7.3
- Added an exception when the file is not found in a estate file query
- Added a modify method for estate files

## v0.7.2
- Fixed a typo where the delete method of estate files returns an error

## v0.7.1
- Added delete to estate file repository

## v0.7.0
- Added upload file Repository

## v0.6.0
- Fixed a bug where the extended claim is not set in the request factory

## v0.5.4
- Fixed a bug where the filters are not wrapped in an array for each column

## v0.5.3
- The return types of the builder methods are now static instead of self

## v0.5.2
- The request all method will now check the response for extract ability of the response

## v0.5.1
- Added missing address fake builder country iso code type setter

## v0.5.0
- Added missing address query fake method
- Added modify method to base builder

## v0.4.1
- Added missing estate files fake method

## v0.4.0
- Moved user repository query to settings repository
- Added regions to settings repository
- Added non-filterable, non-limitable, non-sortable and non-selectable traits to repositories if needed

## v0.3.0
- Added a fake implementation for the estate repository
- Added a fake implementation for the marketplace repository
- Added a fake implementation for the user repository
- Added a ResponseFactory for testing

## v0.2.3
- Added a user repository
- Added a marketplace repository
- Removed the readonly state from repositories, to allow mocking

## v0.2.2
- Added a fallback for on office config values

## v0.2.1
- Changed code style

## v0.2.0
- Added chunked method to repositories
- Added estate files builder
- Added relation repository
- Added recordIds to address builder

## v0.1.0
- Initial release
