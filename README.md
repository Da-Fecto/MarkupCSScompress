# MarkupCSScompress

Automaticly convert all styles in $config->styles to 1 compressed cached CSS file served to the browser.

### How to install

1. Place the module files in **/site/modules/MarkupCSScompress/**.
2. Copy **MarkupCSScompress.php** in MarkupCSScompress folder to your **/site/templates/** folder
3. place **<?php echo $modules->get('MarkupCSScompress')->render(); ?>** in the head where normally the CSS link(s) would be.
4. In your admin, click Modules > Check for new modules
5. Click "install" for MarkupCSScompress

### Settings

- CSS cache expiration *(default 1 day)*
- Caching for superusers *(default no caching, original files served)*

**By default the superuser gets the original CSS files served**

Minimizing CSS is resource expensive. If you want to serve a new copy, put the cache time to 0 and enable caching for superusers. Then visit the page where the CSS get loaded. A fresh copy is served. Don't forget to put the cache back in it's original glory.

- Caching is done with Ryan's **MarkupCache** Module.
- Minifying & absoluting Urls done with Google [Minify](https://code.google.com/p/minify/) (UriRewriter.php saved me more than a headache)

---

- [modules directory](http://modules.processwire.com/modules/markup-csscompress/)
- [forum](http://processwire.com/talk/topic/3964-markupcsscompress/)

---

thanks to: **Ryan Cramer** & **Google Minify Team**
