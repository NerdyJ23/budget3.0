import Vue from 'vue';
import Vuetify from 'vuetify/lib/framework';

import colors from 'vuetify/lib/util/colors'

Vue.use(Vuetify);

export default new Vuetify({
	theme: {
		options: {
			customProperties: true,
		},
		themes: {
			dark: {

				primary: colors.green,
				secondary: colors.shades.black,
				tertiary: colors.blue,
				accent: colors.blue,
				bg: colors.shades.black.lighten2,
			}
		}
	}
});
