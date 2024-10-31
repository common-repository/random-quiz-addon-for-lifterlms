/*
This file includes code copied from and/or based on LifterLMS, copyright LifterLMS, licensed under
GPLv3; see ../license/license.txt for GPLv3 text.

This file was modified by Jonathan Hall
Last modified 2020-12-10
*/

window.wp.hooks.addFilter( 'llms_define_quiz_schema', 'ags-ll-random-quiz-addon', function(quizSchema) {
	
	quizSchema.default.fields.push([
		{
			attribute: 'ags_llrq_random_subset_count',
			id: 'ags-llrq-random-subset',
			label: 'Select Random Subset (Fixed Size)',
			min: 1,
			switch_attribute: 'ags_llrq_random_subset',
			tip: 'Randomly select the specified number of questions for each quiz attempt. Questions that were not used in a previous attempt for the same student will be selected first, followed by the next least used questions, etc. It is not necessary to enable the Randomize Question Order option when using this option.',
			type: 'switch-number',
		},
		{
			attribute: 'ags_llrq_random_subset_user_default',
			id: 'ags-llrq-random-subset-user',
			label: 'Select Random Subset (User Specified Size)',
			min: 0,
			switch_attribute: 'ags_llrq_random_subset_user',
			tip: 'Randomly select a student-specified number of questions for each quiz attempt. The number specified here will be the default number of questions (0 = no default). Questions that were not used in a previous attempt for the same student will be selected first, followed by the next least used questions, etc. It is not necessary to enable the Randomize Question Order option when using this option.',
			type: 'switch-number',
		},
	]);
	
	return quizSchema;

} );