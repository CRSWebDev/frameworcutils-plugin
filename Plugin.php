<?php namespace CRSCompany\FrameworCUtils;

use Backend;
use Illuminate\Support\Facades\Redirect;
use October\Rain\Support\Facades\Event;
use System\Classes\PluginBase;

/**
 * Plugin Information File
 *
 * @link https://docs.octobercms.com/3.x/extend/system/plugins.html
 */
class Plugin extends PluginBase
{
    /**
     * pluginDetails about this plugin.
     */
    public function pluginDetails()
    {
        return [
            'name' => 'FrameworCUtils',
            'description' => 'No description provided yet...',
            'author' => 'CRSCompany',
            'icon' => 'icon-leaf'
        ];
    }

    /**
     * register method, called when the plugin is first registered.
     */
    public function register()
    {
        //
    }

    /**
     * boot method, called right before the request route.
     */
    public function boot()
    {
        Event::listen('cms.router.beforeRoute', function () {
            $settings = \CRSCompany\FrameworCUtils\Models\UtilsSetting::instance();

            // Format: code old_url new_url
            $redirects = $settings->redirects;
            $parsedRedirects = [];
            if (!empty($redirects)) {
                $lines = explode("\n", $redirects);
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (!empty($line)) {
                        $parts = preg_split('/\s+/', $line, 3);
                        if (count($parts) === 3) {
                            $parsedRedirects[] = [
                                'code' => $parts[0],
                                'old_url' => $parts[1],
                                'new_url' => $parts[2],
                            ];
                        }
                    }
                }
            }

            // match current url to old_url in redirects
            $currentUrl = request()->getRequestUri();

            foreach ($parsedRedirects as $redirect) {
                if ($redirect['old_url'] == $currentUrl) {
//                    Redirect::to($redirect['new_url'], $redirect['code']);
                    if ($redirect['code'] == 301) {
                        header('HTTP/1.1 301 Moved Permanently');
                    } else {
                        header('HTTP/1.1 302 Found');
                    }

                    header('Location: ' . $redirect['new_url']);

                    exit;
                }
            }
        });
    }

    static function robots() {
        return 'asdf';
    }

    /**
     * registerComponents used by the frontend.
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'CRSCompany\FrameworCUtils\Components\MyComponent' => 'myComponent',
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label' => 'FWC Utils',
                'description' => 'Nastavení webu jako robots.txt, redirecty atp...',
                'category' => 'FrameworC',
                'icon' => 'icon-safari',
                'class' => \CRSCompany\FrameworCUtils\Models\UtilsSetting::class,
            ]
        ];
    }

    /**
     * registerPermissions used by the backend.
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'crscompany.frameworcutils.some_permission' => [
                'tab' => 'FrameworCUtils',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * registerNavigation used by the backend.
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'frameworcutils' => [
                'label' => 'FrameworCUtils',
                'url' => Backend::url('crscompany/frameworcutils/mycontroller'),
                'icon' => 'icon-leaf',
                'permissions' => ['crscompany.frameworcutils.*'],
                'order' => 500,
            ],
        ];
    }
}
