<template>
	<div
		class="pwPreview"
		data-kirbyblock="steplist"
		@dblclick="open"
		:style="colorVars"
		:data-margintop="content.margintop === true ? 'true' : null"
		:data-marginbottom="content.marginbottom === true ? 'true' : null"
		>

		<pwBlockinfo
			:value="$t('kirbyblock-steplist.name')"
			icon="steplist"
		/>

		<div class="pwGrid">
			<div
				class="pwGridItem"
				:style="gridVars"
				:data-paddingtop="content.paddingtop || defaults['padding-top'] || null"
				:data-paddingright="(content.paddingright !== undefined ? content.paddingright : defaults['padding-right']) === true ? 'true' : null"
				:data-paddingbottom="content.paddingbottom || defaults['padding-bottom'] || null"
				:data-paddingleft="(content.paddingleft !== undefined ? content.paddingleft : defaults['padding-left']) === true ? 'true' : null"
				>

				<div class="contents">

					<!-- Tagline -->
					<pwTagline v-if="settings.tagline" :value="content.tagline" :alignDefault="fieldDefaults['align-tagline']" />

					<!-- Heading -->
					<pwHeading v-if="settings.heading" :value="content.heading" :data-level="content.level" :alignDefault="fieldDefaults['align-heading']" :sizeDefault="fieldDefaults['size-heading']" :textbackgroundDefault="fieldDefaults['textbackground-heading']" :multilineDefault="fieldDefaults['multiline-heading']" :flourishDefault="fieldDefaults['flourish-heading']" />

					<!-- Editor -->
					<pwEditor v-if="settings.editor" :content="content" :alignDefault="fieldDefaults['align-editor']" />

					<!-- Blocks -->
					<div v-if="blockItems.length" class="pwItems" :data-item-style="content.itemstyle || defaults['item-style'] || 'default'" :data-number-align="content.itemnumberalign || defaults['item-number-align'] || 'center'">
						<div v-for="(item, idx) in blockItems" :key="item.id" class="pwItem" :class="{'ishidden': item.isHidden}">
							<div class="pwNumber">{{ idx + 1 }}</div>
							<div class="pwContent">
								<div class="pwHeading" v-if="item.content.heading">{{ item.content.heading }}</div>
								<div class="pwText" v-if="item.content.description" v-html="item.content.description"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import pwBlockinfo from '@/../../kirby-pagewizard/src/components/blockinfo.vue';
import pwTagline from '@/../../kirby-pagewizard/src/components/tagline.vue';
import pwHeading from '@/../../kirby-pagewizard/src/components/heading.vue';
import pwEditor from '@/../../kirby-pagewizard/src/components/editor.vue';
import pwGridStyle from '@/../../kirby-pagewizard/src/mixins/gridStyle.js';
import pwColorStyle from '@/../../kirby-pagewizard/src/mixins/colorStyle.js';

export default {
	components: {
		pwBlockinfo,
		pwTagline,
		pwHeading,
		pwEditor
	},
	mixins: [pwGridStyle, pwColorStyle],
	data() {
		return {
			settings: {},
			fieldDefaults: {},
			defaults: {}
		}
	},
	computed: {
		blockItems() {
			try {
				const raw = this.content.blocks;
				if (!raw) return [];
				return typeof raw === 'string' ? JSON.parse(raw) : raw;
			} catch(e) {
				return [];
			}
		}
	},
	async created() {
		try {
			const response = await this.$api.get('pagewizard/settings/pwsteplist');
			this.settings = response.settings;
			this.fieldDefaults = response.fields || {};
			this.defaults = response.defaults || {};
		} catch (e) {
			this.settings = {};
		}
	}
}
</script>

<style scoped>
div.pwItems {
	display: flex;
	flex-direction: column;
	gap: 1rem;
}
div.pwItem {
	display: flex;
	gap: var(--pwsteplist-item-content-gap, 1rem);
	align-items: center;
}
div.pwItems[data-number-align="top"]    div.pwItem { align-items: flex-start !important; }
div.pwItems[data-number-align="center"] div.pwItem { align-items: center !important; }
div.pwNumber {
	flex-shrink: 0;
	width: 2.5rem;
	height: 2.5rem;
	display: flex;
	align-items: center;
	justify-content: center;
	border-radius: 50%;
	background: var(--pwsteplist-item-number-background, var(--pw-color-heading, var(--color-gray-600)));
	color: var(--pwsteplist-item-number-text, #FFFFFF);
	font-weight: 700;
}

/* centered */
div.pwItems[data-item-style="centered"] div.pwItem {
	flex-direction: column;
	align-items: center;
	text-align: center;
}

/* connected */
div.pwItems[data-item-style="connected"] div.pwItem div.pwNumber {
	position: relative;
}
div.pwItems[data-item-style="connected"] div.pwItem:not(:last-child) div.pwNumber::after,
div.pwItems[data-item-style="connected"][data-number-align="center"] div.pwItem:not(:first-child) div.pwNumber::before {
	content: "";
	position: absolute;
	left: 50%;
	transform: translateX(-50%);
	width: 2px;
	height: 1rem;
	background: var(--pwsteplist-item-connector, var(--pwsteplist-item-number-background, currentColor));
	opacity: 0.4;
}
div.pwItems[data-item-style="connected"] div.pwItem:not(:last-child) div.pwNumber::after   { top: 100%; }
div.pwItems[data-item-style="connected"][data-number-align="center"] div.pwItem:not(:first-child) div.pwNumber::before { bottom: 100%; }

/* minimal */
div.pwItems[data-item-style="minimal"] div.pwNumber {
	width: auto;
	height: auto;
	background: none;
	color: var(--pwsteplist-item-number-background, currentColor);
	border-radius: 0;
	font-size: 1.5em;
	align-items: flex-start;
}
</style>