<div class="mt-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden" 
     x-data="{ 
        apiMode: 'private', 
        lang: 'curl',
        baseUrl: window.location.origin,
        privateEndpoint: '/api/v1/certificates',
        publicEndpoint: '/api/public/ca-certificates'
     }">
    <div class="p-6 border-b border-gray-100 dark:border-gray-700">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    API Documentation
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Learn how to integrate DyDev APP API into your application.</p>
            </div>

            <!-- API Selection -->
            <div class="flex p-1 bg-gray-100 dark:bg-gray-900 rounded-lg">
                <button @click="apiMode = 'private'" 
                        :class="apiMode === 'private' ? 'bg-white dark:bg-gray-800 text-brand-600 dark:text-brand-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700'"
                        class="px-4 py-1.5 text-sm font-medium rounded-md transition-all flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Private API
                </button>
                <button @click="apiMode = 'public'" 
                        :class="apiMode === 'public' ? 'bg-white dark:bg-gray-800 text-brand-600 dark:text-brand-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700'"
                        class="px-4 py-1.5 text-sm font-medium rounded-md transition-all flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2 2 2 0 012 2v.654M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Public API
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12">
        <!-- Sidebar Tabs -->
        <div class="lg:col-span-3 border-r border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 p-4">
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4 px-2">Language</h3>
            <div class="space-y-1">
                <button @click="lang = 'curl'" :class="lang === 'curl' ? 'bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800'" class="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors">
                    <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/></svg>
                    cURL
                </button>
                <button @click="lang = 'js'" :class="lang === 'js' ? 'bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800'" class="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors">
                    <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" xmlns="http://www.w3.org/2000/svg"><path d="M3 3h18v18H3V3zm14.53 14.12c-.52-.31-.76-.6-.76-1.12 0-.49.33-.82.8-.82.46 0 .76.24.96.59l1.1-.64c-.33-.59-.88-1.07-2.01-1.07-1.11 0-1.99.71-1.99 1.9 0 1.13.68 1.72 1.7 2.3.56.32.78.61.78 1.13 0 .58-.45.92-1.05.92-.72 0-1.15-.36-1.38-.9l-1.14.66c.38.86 1.15 1.34 2.54 1.34 1.48 0 2.21-.83 2.21-1.95 0-1.29-.75-1.93-1.76-2.51zm-5.71 1.63c-.38-.6-.62-.83-.99-.83-.41 0-.61.2-.61.53v4.2c0 .33.22.54.56.54.34 0 .56-.21.56-.54V17.5c.34 0 .53.18.89.73l1.15-.7c-.55-.88-1.04-1.22-2.02-1.22-1.24 0-1.76.85-1.76 1.72 0 .84.44 1.51 1.09 1.94-.58.39-.77.92-.77 1.63 0 1.05.73 1.71 1.75 1.71.97 0 1.57-.42 2.05-1.2l-1.12-.66c-.34.56-.59.78-1 .78-.44 0-.68-.26-.68-.61v-2.29z"/></svg>
                    JavaScript
                </button>
                <button @click="lang = 'php'" :class="lang === 'php' ? 'bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800'" class="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors">
                    <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm6.36 14.83c-.08.23-.29.33-.6.33-.31 0-.58-.1-.7-.34l-.87-1.84h-2.12l-.46 1.83c-.08.31-.3.43-.63.43-.32 0-.54-.12-.54-.42 0-.04.01-.09.02-.15l1.65-6.19c.12-.46.39-.68.86-.68.46 0 .71.21.82.63l2.6 6.42zm-5-3.35h1.56l-.78-1.63-.78 1.63zM8.5 15.17l.02.16c0 .3-.22.42-.54.42-.33 0-.55-.12-.63-.43L5.7 8.16C5.69 8.1 5.68 8.05 5.68 8c0-.3.22-.42.54-.42.33 0 .55.12.63.43l1.65 6.16z"/></svg>
                    PHP
                </button>
                <button @click="lang = 'python'" :class="lang === 'python' ? 'bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800'" class="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors">
                    <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" xmlns="http://www.w3.org/2000/svg"><path d="M11.97 2c-2.47 0-2.31 1.07-2.31 1.07l.01 1.11h2.36v.34H8.64s-2.15-.26-2.15 2.1c0 2.37 1.86 2.22 1.86 2.22h1.11v-1.55c0 0-.08-1.86 1.86-1.86h3.1s1.84 0 1.84-1.78V5.21s.11-1.79-1.84-1.79c-1.95-.01-2.45-.42-2.45-.42S13.44 2 11.97 2zM7.46 10.45s-1.84 0-1.84 1.78v1.44s-.11 1.79 1.84 1.79c1.95 0 2.45.42 2.45.42s.53 1.02 2 1.02c2.47 0 2.31-1.07 2.31-1.07l-.01-1.11H12.1v-.34h3.64s2.15.26 2.15-2.1c0-2.37-1.86-2.22-1.86-2.22H7.46zm1.39 1.11c.31 0 .56.25.56.56s-.25.56-.56.56-.56-.25-.56-.56.25-.56.56-.56zm3.33 7.22c-.31 0-.56-.25-.56-.56s.25-.56.56-.56.56.25.56.56-.25.56-.56.56z"/></svg>
                    Python
                </button>
            </div>
        </div>

        <!-- Code Content -->
        <div class="lg:col-span-9 bg-gray-50 dark:bg-gray-900/30">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-[10px] font-bold uppercase" x-text="apiMode === 'private' ? 'Authenticated' : 'Public'"></span>
                        <code class="text-brand-600 dark:text-brand-400" x-text="apiMode === 'private' ? privateEndpoint : publicEndpoint"></code>
                    </h4>
                    <button @click="navigator.clipboard.writeText($refs.codeContent.innerText)" class="p-1 px-2 text-[10px] font-bold text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 uppercase flex items-center gap-1 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Copy Snippet
                    </button>
                </div>

                <div class="relative group">
                    <pre class="rounded-xl bg-white dark:bg-gray-950 p-6 overflow-x-auto border border-gray-200 dark:border-gray-800 shadow-inner"><code class="text-sm font-mono leading-relaxed" x-ref="codeContent"><template x-if="lang === 'curl'"><span class="text-gray-800 dark:text-gray-300" x-text="'curl -X GET ' + baseUrl + (apiMode === 'private' ? privateEndpoint : publicEndpoint) + '\n     -H \'Accept: application/json\'' + (apiMode === 'private' ? '\n     -H \'X-API-KEY: your_api_key_here\'' : '')"></span></template><template x-if="lang === 'js'"><span class="text-gray-800 dark:text-gray-300" x-text="'fetch(\'' + baseUrl + (apiMode === 'private' ? privateEndpoint : publicEndpoint) + '\', {\n  method: \'GET\', \n  headers: {\n    \'Accept\': \'application/json\'' + (apiMode === 'private' ? ',\n    \'X-API-KEY\': \'your_api_key_here\'' : '') + '\n  }\n})\n.then(response => response.json())\n.then(data => console.log(data));'"></span></template><template x-if="lang === 'php'"><span class="text-gray-800 dark:text-gray-300" x-text="'$ch = curl_init();\ncurl_setopt($ch, CURLOPT_URL, \'' + baseUrl + (apiMode === 'private' ? privateEndpoint : publicEndpoint) + '\');\ncurl_setopt($ch, CURLOPT_RETURNTRANSFER, true);\ncurl_setopt($ch, CURLOPT_HTTPHEADER, [\n    \'Accept: application/json\'' + (apiMode === 'private' ? ',\n    \'X-API-KEY: your_api_key_here\'' : '') + '\n]);\n$response = curl_exec($ch);\ncurl_close($ch);\necho $response;'"></span></template><template x-if="lang === 'python'"><span class="text-gray-800 dark:text-gray-300" x-text="'import requests\n\nurl = \'' + baseUrl + (apiMode === 'private' ? privateEndpoint : publicEndpoint) + '\'\nheaders = {\n    \'Accept\': \'application/json\'' + (apiMode === 'private' ? ',\n    \'X-API-KEY\': \'your_api_key_here\'' : '') + '\n}\n\nresponse = requests.get(url, headers=headers)\nprint(response.json())'"></span></template></code></pre>
                </div>

                <div class="mt-4 p-4 rounded-lg bg-orange-50 dark:bg-orange-900/10 border border-orange-100 dark:border-orange-900/20" x-show="apiMode === 'private'">
                    <div class="flex gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-600 dark:text-orange-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-xs text-orange-800 dark:text-orange-300 leading-normal">
                            <strong>Security Note:</strong> Your API Key should never be shared or exposed in client-side code (browsers). Always use environment variables and call these endpoints from your backend servers.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
