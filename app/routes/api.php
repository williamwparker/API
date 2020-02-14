<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// -- Streams
Route::get('stream/stream-comments', 'StreamController@stream_comments');
Route::get('stream/stream-comments/{id}', 'StreamController@stream_comments_id');
Route::post('stream/stream-like-delete', 'StreamController@stream_delete_like');
Route::delete('stream/stream-comments/{id}', 'StreamController@stream_delete_id');
Route::put('stream/stream-comment-update', 'StreamController@stream_comment_update');
Route::post('stream/stream-comment', 'StreamController@stream_save');
Route::get('stream/streams', 'StreamController@streams');
Route::post('stream/stream', 'StreamController@stream');
Route::post('stream/stream-card-contents', 'StreamController@stream_card_contents');
Route::get('stream/stream-comment-cards', 'StreamController@stream_user_comment_cards');
Route::get('stream/stream-like-cards', 'StreamController@stream_user_like_cards');

// -- Curriculum
Route::get('curriculum/curriculum-course/{id}', 'CurriculumController@curriculum_course_id');

// -- Users
Route::get('user/users', 'UserController@users');
Route::get('user/users/{id}', 'UserController@user_id');
Route::get('user/user_cookie/{token}', 'UserController@user_cookie_token');
Route::post('user/user_email', 'UserController@user_email');
Route::get('user/user', 'UserController@user');
Route::get('user/user-update', 'UserController@user_update');
Route::delete('user/user', 'UserController@user_delete');

// -- Analytics
Route::get('analtyics/user_types', 'AnalyticsController@user_types_all');
Route::get('analytics/user_types/{site}', 'AnalyticsController@user_types');
Route::post('analytics/students_act', 'AnalyticsController@students_act');
Route::post('analytics/students_preact', 'AnalyticsController@students_preact');
Route::post('analytics/students_unweightedgpa', 'AnalyticsController@students_unweightedgpa');
Route::post('analytics/students_weightedgpa', 'AnalyticsController@students_weightedgpa');
Route::post('analytics/students_conduct', 'AnalyticsController@students_conduct');
Route::post('analytics/students_attendance', 'AnalyticsController@students_attendance');


Route::get('analytics/site_id/{site}', 'AnalyticsController@site_id');


// -- Settings
Route::get('setting/settings', 'SettingController@settings');
Route::get('setting/settings/{id}', 'SettingController@setting_id');
Route::post('setting/setting', 'SettingController@setting_save');
Route::put('setting/setting-update', 'SettingController@setting_update');

// -- Profiles
Route::get('profile/profiles', 'ProfileController@profiles');
Route::get('profile/profiles/{id}', 'ProfileController@profile_id');
Route::get('profile/user-profile', 'ProfileController@user_profile');
Route::post('profile/profile', 'ProfileController@profile');
Route::put('profile/profile-user', 'ProfileController@profile_user_update');

// -- Directories
Route::get('directory/directories', 'DirectoryController@directories');
Route::get('directory/directory', 'DirectoryController@directory_email');
Route::get('directory/directories/{id}', 'DirectoryController@directory_id');
Route::get('directory/directory-settings', 'DirectoryController@directory_settings');
Route::get('directory/directory-settings-dropdown/{id}', 'DirectoryController@directory_settings_dropdown');
Route::post('directory/directory-settings', 'DirectoryController@directory_settings_save');
Route::put('directory/directory-setting-update', 'DirectoryController@directory_setting_update');
Route::post('directory/email', 'DirectoryController@directory_email');

// -- People
Route::post('people/staff', 'PeopleController@people_staff_page');
Route::post('people/community', 'PeopleController@people_community_page');
Route::post('people/students', 'PeopleController@people_students_page');
Route::post('people/parents', 'PeopleController@people_parents_page');
Route::get('people/staff', 'PeopleController@people_staff_total');
Route::get('people/community', 'PeopleController@people_community_total');
Route::get('people/students', 'PeopleController@people_students_total');
Route::get('people/parents', 'PeopleController@people_parents_total');

Route::post('people/student/portfolio', 'PeopleController@people_student_portfolio');

// -- Parents
Route::get('parent/parents', 'ParentController@parents');
Route::post('parent/parent_student', 'ParentController@parent_student');
Route::get('parent/parents/{id}', 'ParentController@parent_id');
Route::delete('parent/parents/{token}', 'ParentController@parent_delete_token');
Route::post('parent/parent', 'ParentController@parent_save');
Route::put('parent/parent-student-update', 'ParentController@parent_student_update');
Route::post('parent/user_parent', 'ParentController@user_parent_save');
Route::get('parent/email', 'ParentController@user_parent_email');

// -- Students
Route::post('student/student-groups', 'StudentController@student_groups');
Route::post('student/student-groups-save', 'StudentController@student_group_save');
Route::put('student/student-group-update', 'StudentController@student_group_update');

Route::post('student/student-group-students-save', 'StudentController@student_group_student_save');
Route::put('student/student-group-student-update', 'StudentController@student_group_student_update');
Route::post('student/student-group-students', 'StudentController@student_group_student');
Route::delete('student/student-group-students', 'StudentController@student_delete');

Route::get('student/student-picture/{id}', 'StudentController@student_picture_id');

// -- Conduct
Route::post('conduct/conduct-colors', 'ConductController@conduct_colors');

// -- Books
Route::post('books/library', 'BooksController@library');
Route::post('books/books', 'BooksController@books');
Route::post('books/library-save', 'BooksController@library_book_save');
Route::delete('books/library', 'BooksController@library_book_delete');

// -- Apps
Route::get('apps/apps', 'AppsController@apps');

// -- Assessments
Route::post('assessments/assessments', 'AssessmentsController@assessments_email');

// -- SIS
Route::get('sis/abre_ad', 'SISController@abre_ad');
Route::post('sis/abre_airdata', 'SISController@abre_airdata');
Route::post('sis/abre_subscore_categories', 'SISController@abre_airsubscore_categories');
Route::post('sis/abre_attendance', 'SISController@abre_attendance');
Route::post('sis/abre_parent_contacts', 'SISController@abre_parent_contacts');
Route::post('sis/abre_staff', 'SISController@abre_staff');
Route::post('sis/abre_staff_schedules', 'SISController@abre_staff_schedules');
Route::post('sis/abre_student_ACT', 'SISController@abre_student_ACT');
Route::post('sis/abre_student_AP', 'SISController@abre_student_AP');
Route::post('sis/abre_student_assessments', 'SISController@abre_student_assessments');
Route::post('sis/abre_students', 'SISController@abre_students');
Route::post('sis/abre_student_schedules', 'SISController@abre_student_schedules');


// -- Authentication
Route::post('signin', 'AuthController@signin');



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
