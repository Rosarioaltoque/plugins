=== Plugin Name ===
Contributors: csnowden, jpatterson
Support link: http://businessdirectory.squarecompass.com/documentation/
Donate link: http://businessdirectory.squarecompass.com/spareware/
Tags: business, business-directory, business directory, directory, list, listings, free, ajax
Tested up to: 3.0
Stable tag: Trunk

The Business Directory plugin for Wordpress is an easy way to host a free directory page. 

== Description ==

The Business Directory plugin for Wordpress is an easy way to host a free directory page for your readers, affiliates, advertisers, community or club members. Invite them to submit a simple advertisement listing for themselves on your blog.

Listings include company name, a short description, and contact information including a live URL. The plugin includes an admin tab that allows you to approve of listings before they appear live on the site. You can also edit and delete listings.

We will soon have a premium version of this plugin that allows you to promote a listing upgrade to your directory participants; you set the cost and collect via PayPal, they get to upload a logo and appear in the top 'premium' section of your Directory.

Business Directory is a great way to increase the SEO value of your site, and to monetize your community participation.

== Installation ==

These installation instructions for Business Directory assume that you are already somewhat familiar with the operation of WordPress. If you are not familiar with WordPress then we suggest taking a look at http://demoblog.listpipe.com/. Pay particular attention to the following topics: `Login`, `Write A Page`, and `Manage Posts and Pages` sections (The videos can take a minute to load, but they're worth it the wait if you are new to WordPress.).

Follow the steps below to install Business Directory:

1. Backup your database.
2. Download Business Directory from http://wordpress.org/extend/plugins/business-directory/ and unzip it.
3. Unzipping the downloaded file should produce a folder called `business-directory` (if not, try downloading the files again). Upload the that folder and its contents to your `wp-content/plugins` directory inside of your WordPress install.
4. Login to your WordPress admin section and click on the Plugins tab. At this point you should be seeing a list of your plugins. Find Business Directory in your list (it should be in the 'Inactive Plugins' if you are installing Business Directory for the first time) and activate it by clicking the Activate link.
5. Now that your Plugin is active click on the "Biz Directory" link in the botom left of your admin screen, then select "Categories". Edit the "General" category if you desire by clicking the "edit" link, then add new categories by clicking the "Add New Category" link. You need at least one category at all times for your users to be able to add their listings.
6. Next, you will need to write two pages, one to display the directory and the other to display the join form. You can title the pages however you want, but for the simplicity of this installation guide, title one page "Business Directory" and the other "Join the Directory". Although the titles of the pages are irrelevant, the directory itself cannot be on the same page as the join form (i.e. you will need two separate pages for the plugin to work).
7. Somewhere in the content of the page titled 'Business Directory' place the following text: `[bizdir_directory]`. This page will now display your business directory.
8. Somewhere in the content of the page titled 'Join the Directory' place the following text: `[bizdir_addform]`. This page will now display your join form.
9. Manage the directory and submitted listing by clicking on the Manage tab and then selecting the Business Directory link. 

Note: we suggest that you always use the most up-to-date version of WordPress with Business Directory

= Updating =

With a few exceptions (documented below) the best way to upgrade is to use the 'Upgrade Now' button on your plugins page (if your hosting situation is set up to handle that), or by following these steps:

1. Backup your database.
2. Deactivate Business Directory.
3. Delete the old version of Business Directory from your plugins directory (typically `wp-content/plugins`) via ftp.
4. Download the new version of the Business Directory from http://wordpress.org/extend/plugins/business-directory/ and unzip it (Unzipping the downloaded zip file should produce a folder named `business-directory`), and upload it to your plugins directory.
5. Reactivate Business Directory.
6. Now set up your categories by clicking on the "Biz Directory" link in the botom left of your admin screen, then select "Categories". All of your existing listings will be placed in the "General" category. Edit the "General" category if you desire by clicking the "edit" link, then add new categories by clicking the "Add New Category" link. You need at least one category at all times for your users to be able to add their listings. You can move existing listings to different categories by clicking back on "Biz Directory" and then "review" the listings. 

Note: to update to version "0.8 Beta" or higher from a previous version, the database user associated with your site will need to have Alter privilages.
Note: we suggest backing up your database before updating Business Directory.

A special thank you to Jared Mashburn for his work on the categories for version 0.8 Beta.

= Below are a list of versions that have special upgrade requirements =

To upgrade from version "0.5 Beta" to another version follow these steps:

1. Login to your WordPress admin section and deactivate the "0.5 Beta" version of Business Directory.
2. Delete the "0.5 Beta" version of "Business Directory" files from your plugins directory. Do not alter or delete the `wp_biz_listings` table from the database or you most likely will lose all of your listing data.
3. Upload the new version of "Business Directory".
4. Activate the new version of "Business Directory" through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Where can I find more detailed instructions on how to use this plugin? =

Visit our documentation page at http://businessdirectory.squarecompass.com/documentation/ .

= I'm having trouble saving my listings, what can I do? =

There are many reasons that this could be occuring, however we suggest trying the following as a good starting poing:
1. Back up your database
2. Back up the 'wp_biz_listings' table, separately (the 'wp_biz_listings' table may not be there, or may be empty)
3. Deactivate the Business Directory plugin
4. Delete the 'wp_biz_listings' table, specifically
5. Reactivate the Business Directory plugin
6. Import the 'wp_biz_listings' info from your backup

See http://businessdirectory.squarecompass.com/documentation/ for more help.

== Screenshots ==

For screenshots of Business Directory visit our user manual at http://businessdirectory.squarecompass.com/documentation/user-manual/ . 

Even better, you can find an example of this Business Directory at http://businessdirectory.squarecompass.com/example-directory/ .

== Thank You Wordpress ==

We are thrilled to present this plugin for Wordpress