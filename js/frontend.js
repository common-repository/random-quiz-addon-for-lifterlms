/*
This file includes code copied from and/or based on LifterLMS, copyright LifterLMS, licensed under
GPLv3; see ../license/license.txt for GPLv3 text.

This file was modified by Jonathan Hall
Last modified 2020-12-10
*/

LLMS.AGSLLRQAjaxOrig = {
	call: LLMS.Ajax.call
};

LLMS.Ajax.call = function(params) {
	if (params.data && params.data.action && params.data.action === 'quiz_start') {
		var quizLength = jQuery('#ags_llrq_quiz_length').val();
		if (quizLength) {
			params.data['ags_llrq_quiz_length'] = quizLength;
		}
	}
	LLMS.AGSLLRQAjaxOrig.call.call(LLMS.Ajax, params);
};
