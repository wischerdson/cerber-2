import type { RenderResponse, NitroAppPlugin } from 'nitropack'

export default <NitroAppPlugin> function (nitroApp) {
	nitroApp.hooks.hook('render:response', (response: Partial<RenderResponse>) => {
		response.headers && delete response.headers['x-powered-by']
	})
}
