<?php

Route::domain(env('API_URL'))->group(function () {
	// VERSION 2

	Route::name('v2_pass_through')->get('pass-through/{path1?}/{path2?}',                 'HomeController@passThrough');
	Route::name('v2_library_asset')->get('library/asset',                                 'HomeController@libraryAsset');
	Route::name('v2_library_version')->get('library/version',                             'BiblesController@libraryVersion');
	Route::name('v2_library_book')->get('library/book',                                   'V2Controllers\LibraryCatalog\BooksController@book');
	Route::name('v2_library_bookOrder')->get('library/bookorder',                         'V2Controllers\LibraryCatalog\BooksController@bookOrder');
	Route::name('v2_library_bookName')->get('library/bookname',                           'V2Controllers\LibraryCatalog\BooksController@bookNames');
	Route::name('v2_library_chapter')->get('library/chapter',                             'V2Controllers\LibraryCatalog\BooksController@chapters');

    Route::name('v2_library_language')->get('library/language',                           'V2Controllers\LibraryCatalog\LanguageController@languageListing');
    Route::name('v2_volume_history')->get('library/volumehistory',                        'V2Controllers\LibraryCatalog\LibraryVolumeController@history');
    Route::name('v2_library_volumeLanguage')->get('library/volumelanguage',               'V2Controllers\LibraryCatalog\LanguageController@volumeLanguage');
	Route::name('v2_library_volumeLanguageFamily')->get('library/volumelanguagefamily',   'V2Controllers\LibraryCatalog\LanguageController@volumeLanguageFamily');
	Route::name('v2_country_lang')->get('country/countrylang',                            'V2Controllers\LibraryCatalog\LanguageController@CountryLang');

	Route::name('v2_library_verseInfo')->get('library/verseinfo',                         'VerseController@info');
	Route::name('v2_library_numbers')->get('library/numbers',                             'NumbersController@customRange');
	Route::name('v2_library_metadata')->get('library/metadata',                           'V2Controllers\LibraryCatalog\LibraryMetadataController@index');
	Route::name('v2_library_volume')->get('library/volume',                               'V2Controllers\LibraryCatalog\LibraryVolumeController@libraryVolume');

	Route::name('v2_volume_organization_list')->get('library/volumeorganization',         'OrganizationsController@index');

	// TODO: Cache below Routes
	Route::name('v2_library_organization')->get('library/organization',                   'OrganizationsController@index');
	Route::name('v2_audio_location')->get('audio/location',                               'AudioController@location');
	Route::name('v2_audio_path')->get('audio/path',                                       'AudioController@index');
	Route::name('v2_audio_timestamps')->get('audio/versestart',                           'AudioController@timestampsByReference');
	Route::name('v2_text_font')->get('text/font',                                         'TextController@fonts');
	Route::name('v2_text_verse')->get('text/verse',                                       'TextController@index');
	Route::name('v2_text_search')->get('text/search',                                     'TextController@search');
	Route::name('v2_text_search_group')->get('text/searchgroup',                          'TextController@searchGroup');
	Route::name('v2_video_location')->get('video/location',                               'FilmsController@location');
	Route::name('v2_video_path')->get('video/path',                                       'FilmsController@videoPath');
	Route::name('v2_api_versionLatest')->get('api/apiversion',                            'HomeController@versionLatest');
	Route::name('v2_api_apiReply')->get('api/reply',                                      'HomeController@versionReplyTypes');
	Route::name('v2_api_jesusFilms')->get('library/jesusfilm',                            'ResourcesController@jesusFilmListing');

	// VERSION 3
	// What can man do against such reckless hate
	Route::prefix('v3')->group(function () {
		Route::name('v3_query')->get('search',                                           'V3Controller@search');
		Route::name('v3_books')->get('books',                                            'V3Controller@books');
	});

	// VERSION 4

	// VERSION 4 | BIBLE
	Route::name('v4_access_groups.index')->get('access/groups',                      'AccessGroupController@index');
	Route::name('v4_access_groups.store')->post('access/groups/',                    'AccessGroupController@store');
	Route::name('v4_access_groups.show')->get('access/groups/{group_id}',            'AccessGroupController@show');
	Route::name('v4_access_groups.update')->put('access/groups/{group_id}',          'AccessGroupController@update');
	Route::name('v4_access_groups.destroy')->delete('access/groups/{group_id}',      'AccessGroupController@destroy');

	Route::name('v4_bible_filesets.types')->get('bibles/filesets/media/types',                      'BibleFileSetsController@mediaTypes');
	Route::name('v4_bible_filesets.podcast')->get('bibles/filesets/{fileset_id}/podcast',           'BibleFileSetsController@podcast');
	Route::name('v4_bible_filesets.download')->get('bibles/filesets/{fileset_id}/download',         'BibleFileSetsController@download');
	Route::name('v4_bible_filesets.copyright')->get('bibles/filesets/{fileset_id}/copyright',       'BibleFileSetsController@copyright');
	Route::name('v4_bible_filesets.chapter')->get('bibles/filesets/{fileset_id}/{book}/{chapter}',  'TextController@index');
	Route::name('v4_bible_filesets.show')->get('bibles/filesets/{fileset_id?}',                     'BibleFileSetsController@show');
	Route::name('v4_bible_filesets.update')->put('bibles/filesets/{fileset_id}',                    'BibleFileSetsController@update');
	Route::name('v4_bible_filesets.store')->post('bibles/filesets/',                                'BibleFileSetsController@store');
	Route::name('v4_bible.links')->get('bibles/links',                                              'BibleLinksController@index');
	Route::name('v4_bible.allBooks')->get('bibles/books/',                                          'BooksController@index');
	Route::name('v4_text_search')->get('search',                                                    'TextController@search');
	Route::name('v4_bible.books')->get('bibles/{bible_id}/book/{book?}',                            'BiblesController@books');
    Route::name('v4_bible.archival')->get('bibles/archival',                                        'BiblesController@archival');
	Route::name('v4_bible.one')->get('bibles/{bible_id}',                                           'BiblesController@show');
	Route::name('v4_bible.all')->get('bibles',                                                      'BiblesController@index');
	Route::name('v4_timestamps')->get('timestamps',                                                 'AudioController@availableTimestamps');
	Route::name('v4_timestamps.tag')->get('timestamps/{fileset_id}/{query}',                        'AudioController@timestampsByTag');
	Route::name('v4_timestamps.verse')->get('timestamps/{fileset_id}/{book}/{chapter}',             'AudioController@timestampsByReference');

	// VERSION 4 | WIKI
	Route::name('v4_countries.all')->get('countries',                                     'CountriesController@index');
	Route::name('v4_countries.jsp')->get('countries/joshua-project/',                     'CountriesController@joshuaProjectIndex');
	Route::name('v4_countries.one')->get('countries/{country_id}',                        'CountriesController@show');
	Route::name('v4_languages.all')->get('languages',                                     'LanguagesController@index');
	Route::name('v4_languages.one')->get('languages/{language_id}',                       'LanguagesController@show');
	Route::name('v4_alphabets.all')->get('alphabets',                                     'AlphabetsController@index');
	Route::name('v4_alphabets.one')->get('alphabets/{alphabet_id}',                       'AlphabetsController@show');
	Route::name('v4_alphabets.store')->post('alphabets',                                  'AlphabetsController@store');
	Route::name('v4_alphabets.update')->put('alphabets/{alphabet_id}',                    'AlphabetsController@update');
	Route::name('v4_numbers.range')->get('numbers/range',                                 'NumbersController@customRange');
	Route::name('v4_numbers.all')->get('numbers/',                                        'NumbersController@index');
	Route::name('v4_numbers.one')->get('numbers/{number_id}',                             'NumbersController@show');

	// VERSION 4 | USERS
	Route::name('v4_user.index')->get('users',                                            'UsersController@index');
	Route::name('v4_user.store')->post('users',                                           'UsersController@store');
	Route::name('v4_user.show')->get('users/{user_id}',                                   'UsersController@show');
	Route::name('v4_user.update')->put('users/{user_id}',                                 'UsersController@update');
	Route::name('v4_user.destroy')->delete('users/{user_id}',                             'UsersController@destroy');
	Route::name('v4_user.login')->post('users/login',                                     'UsersController@login');
	Route::name('v4_user.geolocate')->get('users/geolocate',                              'UsersController@geoLocate');

	// VERSION 4 | USER PASSWORDS
	Route::name('v4_user.password_reset')->post('users/password/reset',                   'UserPasswordsController@validatePasswordReset');
	Route::name('v4_user.password_email')->post('users/password/email',                   'UserPasswordsController@triggerPasswordResetEmail');

	// VERSION 4 | USER ACCOUNTS LOGIN

	Route::name('v4_user.oAuth')->get('users/login/{driver}',                             'Auth\LoginController@redirectToProvider');
	Route::name('v4_user.oAuthCallback')->get('users/login/{driver}/callback',            'Auth\LoginController@handleProviderCallback');

	// VERSION 4 | USER ACCOUNTS
	Route::name('v4_user_accounts.index')->get('/accounts',                      'UserAccountsController@index');
	Route::name('v4_user_accounts.show')->get('/accounts/{account_id}',          'UserAccountsController@show');
	Route::name('v4_user_accounts.store')->post('/accounts',                     'UserAccountsController@store');
	Route::name('v4_user_accounts.update')->put('/accounts/{account_id}',        'UserAccountsController@update');
	Route::name('v4_user_accounts.destroy')->delete('/accounts/{account_id}',    'UserAccountsController@destroy');

	// VERSION 4 | USER NOTES
	Route::name('v4_notes.index')->get('users/{user_id}/notes',                           'UserNotesController@index');
	Route::name('v4_notes.show')->get('users/{user_id}/notes/{note_id}',                  'UserNotesController@show');
	Route::name('v4_notes.store')->post('users/{user_id}/notes',                          'UserNotesController@store');
	Route::name('v4_notes.update')->put('users/{user_id}/notes/{note_id}',                'UserNotesController@update');
	Route::name('v4_notes.destroy')->delete('users/{user_id}/notes/{note_id}',            'UserNotesController@destroy');

	// VERSION 4 | USER BOOKMARKS
	Route::name('v4_bookmarks.index')->get('users/{user_id}/bookmarks',                     'UserNotesController@index');
	Route::name('v4_bookmarks.show')->get('users/{user_id}/bookmarks/{bookmark_id}',        'UserNotesController@show');
	Route::name('v4_bookmarks.store')->post('users/{user_id}/bookmarks',                    'UserNotesController@store');
	Route::name('v4_bookmarks.update')->put('users/{user_id}/bookmarks/{bookmark_id}',      'UserNotesController@update');
	Route::name('v4_bookmarks.destroy')->delete('users/{user_id}/bookmarks/{bookmark_id}',  'UserNotesController@destroy');

	// VERSION 4 | USER HIGHLIGHTS

	Route::name('v4_highlights.index')->get('users/{user_id}/highlights',                      'UserHighlightsController@index');
	Route::name('v4_highlights.store')->post('users/{user_id}/highlights',                     'UserHighlightsController@store');
	Route::name('v4_highlights.update')->put('users/{user_id}/highlights/{highlight_id}',      'UserHighlightsController@update');
	Route::name('v4_highlights.destroy')->delete('users/{user_id}/highlights/{highlight_id}',  'UserHighlightsController@destroy');

	Route::name('v4_organizations.all')->get('organizations/',                            'OrganizationsController@index');
	Route::name('v4_organizations.one')->get('organizations/{organization_id}',           'OrganizationsController@show');
	Route::name('v4_api.versions')->get('/api/versions',                                  'HomeController@versions');
	Route::name('v4_api.versionLatest')->get('/api/versions/latest',                      'HomeController@versionLatest');
	Route::name('v4_api.replyTypes')->get('/api/versions/replyTypes',                     'HomeController@versionReplyTypes');
	Route::name('v4_api.sign')->get('sign',                                               'HomeController@signedUrls');

	// VERSION 4 | PROJECTS
	Route::name('v4_projects.index')->get('projects',                                      'ProjectsController@index');
	Route::name('v4_projects.show')->get('projects/{project_id}',                          'ProjectsController@show');
	Route::name('v4_projects.update')->put('projects/{project_id}',                        'ProjectsController@update');
	Route::name('v4_projects.store')->post('projects',                                     'ProjectsController@store');
	Route::name('v4_projects.destroy')->delete('projects/{project_id}',                    'ProjectsController@destroy');

	Route::name('v4_projects_oAuthProvider.index')->get('projects/{project_id}/oauth-providers/',          'ProjectOAuthProvidersController@index');
	Route::name('v4_projects_oAuthProvider.show')->get('projects/{project_id}/oauth-providers/{id}',       'ProjectOAuthProvidersController@show');
	Route::name('v4_projects_oAuthProvider.update')->put('projects/{project_id}/oauth-providers/{id}',     'ProjectOAuthProvidersController@update');
	Route::name('v4_projects_oAuthProvider.store')->post('projects/{project_id}/oauth-providers',          'ProjectOAuthProvidersController@store');
	Route::name('v4_projects_oAuthProvider.destroy')->delete('projects/{project_id}/oauth-providers/{id}', 'ProjectOAuthProvidersController@destroy');

	// VERSION 4 | UTILITY
	Route::name('v4_api.buckets')->get('/api/buckets',                                    'HomeController@buckets');
    Route::name('v4_api.stats')->get('/stats',                                            'HomeController@stats');

	// VERSION 4 | ERRORS
	Route::name('v4_api.logs')->get('sign',                                               'HomeController@signedUrls');

	// VERSION 4 | DEEPLINK
	Route::name('v4_deeplinking.index')->get('app/deeplinking',                          'MobileAppsController@redirectDeepLink');

	// VERSION 4 | CONNECTIONS

	Route::name('v4_connections_jfm.sync')->get('connections/jesus-film/sync',           'Connections\ArclightController@sync');
	Route::name('v4_connections_jfm.index')->get('connections/jesus-film/{iso}',         'Connections\ArclightController@index');

	Route::name('v4_connections_grn.sync')->get('connections/grn/sync',                  'Connections\GRNController@sync');
	Route::name('v4_connections_grn.index')->get('connections/grn/{iso}',                'Connections\GRNController@index');

	// VERSION 4 | Resources

	Route::name('v4_resources.index')->get('resources',                                      'ResourcesController@index');
	Route::name('v4_resources.show')->get('resources/{resource_id}',                         'ResourcesController@show');
	Route::name('v4_resources.update')->put('resources/{resource_id}',                       'ResourcesController@update');
	Route::name('v4_resources.store')->post('resources',                                     'ResourcesController@store');
	Route::name('v4_resources.destroy')->delete('resources/{resource_id}',                   'ResourcesController@destroy');

	// VERSION 4 | ARTICLES
	Route::name('v4_articles.index')->get('articles',                                      'ArticlesController@index');
	Route::name('v4_articles.show')->get('articles/{article_id}',                          'ArticlesController@show');
	Route::name('v4_articles.update')->put('articles/{article_id}',                        'ArticlesController@update');
	Route::name('v4_articles.store')->post('articles',                                     'ArticlesController@store');
	Route::name('v4_articles.destroy')->delete('articles/{article_id}',                    'ArticlesController@destroy');

});