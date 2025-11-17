# Advanced Media Filters for WordPress

A lightweight, professional enhancement to add new filters to the WordPress Media Library. This component allows you to filter your media library by specific MIME types (e.g., `image/jpeg`, `application/pdf`).

## Features

* **Filter by MIME Type:** Adds a new dropdown to the Media Library screen to filter all media by its specific MIME type.
* **Dynamic and Performant:** The list of MIME types is dynamically generated from your library's content and cached in a transient for fast performance.
* **Lightweight:** No unnecessary scripts or styles. Integrates directly with the native WordPress admin UI.
* **Professionally Coded:** Built using modern, object-oriented PHP (OOP) and follows the Singleton design pattern to ensure efficient loading.

## Installation

Follow these steps to integrate this enhancement into your theme:

1.  **Copy the Folder:**
    Download or clone this repository. Take the `wp-advanced-media-filters` folder and place it directly inside your active theme's main directory.

    Your theme structure should look like this:
    ```
    /wp-content/themes/your-active-theme/
    ├── wp-advanced-media-filters/
    │   ├── includes/
    │   │   └── class-hussainas-media-filters.php
    │   ├── load.php
    │   └── README.md
    ├── functions.php
    └── ... (your other theme files)
    ```

2.  **Include the Loader in `functions.php`:**
    Open your active theme's `functions.php` file and add the following line of PHP code, preferably at the end of the file:

    ```php
    // Load the advanced media filters enhancement.
    require_once( get_template_directory() . '/wp-advanced-media-filters/load.php' );
    ```

    *Note: If you are using a **child theme**, you should use `get_stylesheet_directory()` instead:*
    ```php
    // Load the advanced media filters enhancement from a child theme.
    require_once( get_stylesheet_directory() . '/wp-advanced-media-filters/load.php' );
    ```

3.  **That's it!** The filter will now be active in your Media Library.

## How to Use

1.  From your WordPress Dashboard, navigate to **Media** > **Library**.
2.  **IMPORTANT:** This filter **only appears in the "List View"**. By default, WordPress uses a "Grid View". Please click the **List View icon** (which looks like horizontal lines 
    ) located at the top-left of the media area, next to the "Add Media File" button.
3.  Once you are in List View, you will see a new dropdown menu labeled "**All MIME Types**" next to the default date filter.
4.  Select a MIME type from the list (e.g., `image/png`) and click the **"Filter"** button.
5.  The Media Library will update to show only files of that specific type.

## License

This code is released under the GPLv2 (or later) license, consistent with WordPress.
