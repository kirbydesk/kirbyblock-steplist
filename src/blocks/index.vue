<template>
	<!-- the block in the panel: its info bar and the shared preview of the
	     Project Wizard (the project's look, the real content); a double
	     click opens the block -->
	<div class="pwPreview" data-kirbyblock="steplist" @dblclick="open">

		<pwBlockinfo
			:value="$t('kirbyblock-steplist.name')"
			icon="steplist"
		/>

		<pw-block-panel-preview type="pwsteplist" :content="content" />

	</div>
</template>

<script>
import pwBlockinfo from '@/../../kirby-pagewizard/src/components/blockinfo.vue';

export default {
	components: {
		pwBlockinfo
	}
}
</script>
