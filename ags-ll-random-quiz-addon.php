<?php
/*
Plugin Name:       Random Quiz Generator for LifterLMS
Plugin URI:        https://wpzone.co/
Description:       Randomize your quizzes and automatically generate a random question set for each attempt in LifterLMS.
Version:           1.0.2
Author:            WP Zone
License:           GPLv3
License URI:       http://www.gnu.org/licenses/gpl.html
GitLab Plugin URI: https://gitlab.com/aspengrovestudios/random-quiz-addon-for-lifterlms/
Text Domain:       ags-ll-random-quiz-addon
*/

/*
Despite the following, this project is licensed exclusively
under GPLv3 (no future versions).
This statement modifies the following text.

Random Quiz Addon for LifterLMS
Copyright (C) 2024  WP Zone

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.

========

Credits:

This plugin includes code based on parts of WordPress by Automattic, released under GPLv2+,
licensed under GPLv3 (see wp-license.txt in the license directory for the copyright, license,
and additional credits applicable to WordPress, and the license.txt file in the license directory
for GPLv3 text).

This plugin includes code copied from and/or based on LifterLMS, copyright LifterLMS, licensed under
GPLv3; see license/license.txt for GPLv3 text.

This file was modified by Jonathan Hall
Last modified 2020-12-10

*/

class AGS_LifterLMSRandomQuizzes {

	const VERSION = '1.0.2';
	
	static $pluginBaseUrl;
	
	static function setup() {
		self::$pluginBaseUrl = plugin_dir_url(__FILE__);
		
		/* Hooks */
		add_action('wp_enqueue_scripts', array(__CLASS__, 'frontendScripts'));
		add_action('admin_enqueue_scripts', array(__CLASS__, 'adminScripts'));
		add_action('init', array(__CLASS__, 'maybeOverrideQuizQuestions'));
		add_action('lifterlms_before_start_quiz', array(__CLASS__, 'maybeInjectQuizLengthField'));
		add_filter( 'llms_get_quiz_properties', function($props) {
			
			$props['ags_llrq_random_subset'] = 'yesno';
			$props['ags_llrq_random_subset_count'] = 'int';
			$props['ags_llrq_random_subset_user'] = 'yesno';
			$props['ags_llrq_random_subset_user_default'] = 'int';
			
			return $props;
			
		} );
		
	}
	
	static function adminScripts() {
		wp_enqueue_script('ags-ll-random-quiz-addon-admin', self::$pluginBaseUrl.'js/admin.js', array('jquery', 'wp-hooks' /*, 'wp-i18n'*/), self::VERSION, true);
	}
	
	static function frontendScripts() {
		wp_enqueue_script('ags-ll-random-quiz-addon', self::$pluginBaseUrl.'js/frontend.js', array('jquery', 'llms' /*, 'wp-i18n'*/), self::VERSION, true);
	}
	
	
	static function maybeInjectQuizLengthField() {
		
		// lifterlms\templates\course\complete-lesson-link.php
		global $post;
		$quiz = llms_get_post( $post );
		
		if ( $quiz && llms_parse_bool( $quiz->get('ags_llrq_random_subset_user') ) ) {
			ob_start();
			add_action('lifterlms_after_start_quiz', array(__CLASS__, 'injectQuizLengthField'));
		}
	}
	
	static function injectQuizLengthField() {
		
		remove_action('lifterlms_after_start_quiz', array(__CLASS__, 'injectQuizLengthField'));
		
		$startQuizHtml = ob_get_clean();
		$buttonStartPos = stripos($startQuizHtml, '<button ');
		
		if ($buttonStartPos === false) {
			echo($startQuizHtml);
			return;
		}
		
		echo( substr($startQuizHtml, 0, $buttonStartPos) );
		
		// lifterlms\templates\course\complete-lesson-link.php
		global $post;
		$quiz = llms_get_post( $post );
		$count = $quiz->get('ags_llrq_random_subset_user_default');
			
?>
<div class="ags-llrq-quiz-length">
	<label>
		<span>Number of questions:</span>
		<input type="number" id="ags_llrq_quiz_length" name="ags_llrq_quiz_length" min="1"<?php echo( !$count || $count < 0 ? '' : ' value="'.( (int) $count ).'"' ); ?>>
	</label>
</div>
<?php
		
		echo( substr($startQuizHtml, $buttonStartPos) );

	}
	
	
	static function maybeOverrideQuizQuestions() {
		add_action( 'wp_ajax_quiz_start', array( __CLASS__, 'overrideQuizQuestions' ), 1 );
		add_action( 'wp_ajax_nopriv_quiz_start', array( __CLASS__, 'overrideQuizQuestions' ), 1 );
		
		if ( function_exists('llms_verify_nonce') && llms_verify_nonce( '_llms_take_quiz_nonce', 'take_quiz', 'POST' ) && isset( $_POST['quiz_id'] ) && isset( $_POST['associated_lesson'] ) ) {
			self::overrideQuizQuestions();
		}
	}
	
	static function overrideQuizQuestions() {
		
		add_filter( 'llms_quiz_get_questions', function($questions, $questionManager) {
			$quiz = $questionManager->parent;
			
			
			if ( llms_parse_bool( $quiz->get('ags_llrq_random_subset_user') ) ) {
				
				if (isset($_REQUEST['ags_llrq_quiz_length'])) {
					$count = (int) $_REQUEST['ags_llrq_quiz_length'];
				}
				if (empty($count) || $count < 0) {
					$count = $quiz->get('ags_llrq_random_subset_user_default');
				}
				if (!$count || $count < 0) {
					$count = 1;
				}
				
				
			} else if ( llms_parse_bool( $quiz->get('ags_llrq_random_subset') ) ) {
				$count = $quiz->get('ags_llrq_random_subset_count');
				if (!$count || $count < 0) {
					$count = 1;
				}
			} else {
				return $questions;
			}
			
			$student = llms_get_student();
			if ( ! $student ) {
				throw new Exception();
			}
			
			$questionsById = [];
			
			foreach ( $questions as $question ) {
				$questionsById[ $question->get('id') ] = $question;
			}
			
			
			$otherAttempts = $student->quizzes()->get_attempts_by_quiz( $quiz->get('id') );
			
			$previousQuestionIds = array_combine(
				array_keys($questionsById),
				array_fill(0, count($questionsById), 0)
			);
			
			foreach ( $otherAttempts as $attempt ) {
				foreach ( $attempt->get_question_objects() as $question ) {
					++$previousQuestionIds[ $question->get('id') ];
				}
			}
			
			$questionIdsByOccurrences = [];
			foreach ( $previousQuestionIds as $id => $occurrences ) {
				if ( isset($questionIdsByOccurrences[$occurrences]) ) {
					$questionIdsByOccurrences[$occurrences][] = $id;
				} else {
					$questionIdsByOccurrences[$occurrences] = [ $id ];
				}
			}
			
			$newQuestionIds = [];
			
			foreach ($questionIdsByOccurrences as $level => $ids) {
				shuffle($ids);
				$newQuestionIds = array_merge( $newQuestionIds, array_slice( $ids, 0, $count - count($newQuestionIds) ) );
				if (count($newQuestionIds) == $count) {
					break;
				}
			}
			
			$newQuestions = array_values(
				array_intersect_key(
					$questionsById,
					array_flip($newQuestionIds)
				)
			);
			
			shuffle($newQuestions);
			
			return $newQuestions;
			
		}, 10, 2);
	}
	
	
}

AGS_LifterLMSRandomQuizzes::setup();