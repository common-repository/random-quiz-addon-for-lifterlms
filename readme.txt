=== Random Quiz Generator for LifterLMS ===
Contributors: aspengrovestudios, annaqq
Tags: lifterlms, quiz randomizer, random quizzes, random quiz generator
Requires at least: 5.0
Tested up to: 6.6.1
Stable tag: 1.0.2
License: GNU General Public License version 3
License URI: https://www.gnu.org/licenses/gpl-3.0.en.html

Randomize your quizzes and automatically generate a random question set for each attempt in LifterLMS.

== Description ==
**Random Quiz Generator for [LifterLMS](https://wordpress.org/plugins/lifterlms/) can pull a random set of questions from your quiz so users never get the same question twice when retaking or setting up a practice quiz.**

## Automated Quiz Randomizer Benefits
- Expands functionality of LifterLMS
- Automatically generate random question set for each attempt
- Great for practice quiz, adding variety, and second chance testing
- Randomly pulls questions from a quiz
- Limit the number of questions that appear in each test
- Cycles through questions randomly AND based on the number of attempts so a user can retest once all the questions are used up
- Select Random Subset (User Specified Size) option that allows the student to specify how many questions should be in the quiz attempt
- Limit number of times a student can retest


## How it Works
The Random Quiz Generator plugin seamlessly integrates with the [LifterLMS](https://wordpress.org/plugins/lifterlms/) quiz editor. Simply click the “Select Random Subset” button from the quiz builder and set your question count.
Once all the questions are used up, Random Quiz Generator will pull from questions with the least number of attempts.

For example, if you create a test with 100 questions and set the subset to 20, users will be given 20 questions at random per attempt. Every time your user retakes the quiz they will be given a completely different set of questions.


*Note: Quizzes must be configured to not allow more attempts than can be filled by the total number of questions. If there are 20 questions and each attempt uses 10 questions, configure to no more than 2 attempts.*

## Love Random Quiz Generator for LifterLMS?
[Aspen Grove Studios](https://aspengrovestudios.com/) has built a whole bunch of neat themes, plugins, add-ons, and services. Check out our other crowd favorites and don’t forget to leave a ⭐️⭐️⭐️⭐️⭐️ review to help others in the community decide.

- [WPLayouts](https://wordpress.org/plugins/wp-layouts/) – Organize, import, and export your theme layouts from the cloud
- [Replace Image](https://wordpress.org/plugins/replace-image/) – Keep the same URL when uploading to the WordPress media library
- [Potent Donations for WooCommerce](https://wordpress.org/plugins/donations-for-woocommerce/) – Acceptance donations through your WooCommerce store

Enjoy!

== Installation ==
1. Click "Plugins" > "Add New" in the WordPress admin menu.

2. Search for "Random Quiz Generator Addon for LifterLMS".

3. Click "Install Now".

4. Click "Activate Plugin". Alternatively, you can manually upload the plugin to your wp-content/plugins directory. Once you have installed and activated the plugin, the "Select Random Subset" option should be visible when creating or editing quizzes in the course builder. There is no other user interface.

== Frequently Asked Questions ==
= Is there a premium version of Random Quiz Generator for LifterLMS? =

All the features are completely free and we do not have any premium upsell for Random Quiz Generator for LifterLMS.

= Where can I get LifterLMS? =
The free version of [LifterLMS](https://wordpress.org/plugins/lifterlms/) is right here on the WordPress repository.

== Screenshots ==
1. Location of the "Select Random Subset" setting that this plugin adds to Quiz settings in the LifterLMS course builder.

== Changelog ==

### Version 1.0.2
* Quiz questions are now reused in order of least number of occurrences in attempts for the current student if there are no or insufficient remaining unused questions for the current quiz and student.
* Added a "Select Random Subset (User Specified Size)" option that allows the student to specify how many questions should be in the quiz attempt.


### 1.0.1
* Fix: Fatal error if LifterLMS is not active.

### 1.0.0 * Initial release
* Expands functionality of LifterLMS
* Automatically generate random question set for each attempt
* Great for practice quiz, adding variety, and second chance testing
