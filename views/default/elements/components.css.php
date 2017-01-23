/* <style> /**/
	
/**
 * Layout Object CSS
 *
 * Image blocks, lists, tables, gallery, messages
 */

/* ***************************************
	Image Block
*************************************** */
.elgg-image-block {
	padding: 10px 0;
}
.elgg-image-block .elgg-image {
	float: left;
	margin-right: 8px;
}
.elgg-image-block .elgg-image-alt {
	float: right;
	margin-left: 8px;
}

/* ***************************************
	List
*************************************** */
.elgg-list {
	margin: 5px 0;
	clear: both;
}
.elgg-list > li {
	border-bottom: 1px solid #DCDCDC;
}
.elgg-item h3 a {
	padding-bottom: 4px;
}
.elgg-item > .elgg-subtext {
	margin-bottom: 4px;
}
.elgg-item .elgg-content {
	margin: 10px 0;
}
.elgg-content {
	clear: both;
}

.elgg-module > .elgg-body > .elgg-list, /* margin for group modules */
.elgg-module .elgg-widget-content > .elgg-list { /* margin for profile and dashboard widgets */
    margin-top: 0;
}

/* ***************************************
	Gallery
*************************************** */
.elgg-gallery {
	border: none;
	margin-right: auto;
	margin-left: auto;
}
.elgg-gallery td {
	padding: 5px;
}
.elgg-gallery-fluid > li {
	float: left;
}
.elgg-gallery-users > li {
	margin: 0 2px;
}

/* ***************************************
	Tables
*************************************** */
.elgg-table {
	width: 100%;
	border-top: 1px solid #DCDCDC;
}
.elgg-table td, .elgg-table th {
	padding: 4px 8px;
	border: 1px solid #DCDCDC;
}
.elgg-table th {
	background-color: #DDD;
}
.elgg-table tr:nth-child(odd) {
	background-color: #FFF;
}
.elgg-table tr:nth-child(even) {
	background-color: #F0F0F0;
}
.elgg-table-alt {
	width: 100%;
	border-top: 1px solid #DCDCDC;
}
.elgg-table-alt th {
	background-color: #EEE;
	font-weight: bold;
}
.elgg-table-alt td, .elgg-table-alt th {
	padding: 6px 0;
	border-bottom: 1px solid #DCDCDC;
}
.elgg-table-alt td:first-child {
	width: 200px;
}
.elgg-table-alt tr:hover {
	background: #E4E4E4;
}

/* ***************************************
	Owner Block
*************************************** */
.elgg-owner-block {
	margin-bottom: 20px;
}

/* ***************************************
	Messages
*************************************** */
.elgg-message {
	color: #FFF;
	display: block;
	padding: 10px 20px;
	cursor: pointer;
	opacity: 0.9;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
	border-radius: 3px;
}
.elgg-state-success {
	background-color: #090;
}
.elgg-state-error {
	background-color: #F00;
}
.elgg-state-notice {
	background-color: #5097CF;
}
.elgg-message a {
	color: inherit;
	text-decoration: underline;
}
.elgg-box-error {
	margin-top: 10px;
	padding: 20px;
	color: #B94A48;
	background-color: #F8E8E8;
	border: 1px solid #E5B7B5;
	border-radius: 5px;
}
.elgg-box {
	margin: 10px 0;
	padding: 20px;
	border-radius: 5px;
	box-sizing: border-box;
}
.elgg-box.elgg-state-error {
	color: #B94A48;
	background-color: #F8E8E8;
	border: 1px solid #E5B7B5;
}
.elgg-box.elgg-state-notice {
	color: #3B8BC9;
	background-color: #E7F1F9;
	border: 1px solid #B1D1E9;
}
.elgg-box.elgg-state-success {
	color: #397F2E;
	background-color: #EAF8E8;
	border: 1px solid #AADEA2;
}
.elgg-box.elgg-state-warning {
	color: #6B420F;
	background-color: #FCF8E4;
	border: 1px solid #EDDC7D;
}

/* ***************************************
	River
*************************************** */
.elgg-module .elgg-list-river {
	border-top: none;
}
.elgg-list-river > li {
	border: 1px solid #DCDCDC;
	margin-bottom: 20px;
}
.elgg-river-timestamp {
	color: #666;
	font-size: 85%;
	font-style: italic;
	line-height: 1.2em;
}
.elgg-list-river > li > .elgg-image-block {
	padding: 20px 20px 10px;
}
.elgg-river-social {
	padding: 10px 20px;
	background: #f0f0f0;
	border-top: 1px solid #DCDCDC;
}
.elgg-river-responses > .elgg-list {
	margin: 0px;
}
.elgg-river-responses > .elgg-list > li:last-child {
	border-bottom: 0px;
}

.elgg-listing-full-body {
	padding: 0 0 20px 0;
}
.elgg-listing-full-social {
	padding: 10px 20px;
	background: #f0f0f0;
	border: 1px solid #DCDCDC;
}

.elgg-menu-social a {
	color: #AAA;
}
.elgg-menu-social > li {
	margin-right: 10px;
}
.elgg-menu-social > li:hover a {
	color: #4690D6;
	text-decoration: none;
}

.elgg-river-attachments,
.elgg-river-message,
.elgg-river-content {
	margin: 0 20px 20px;
}
.elgg-river-attachments .elgg-avatar,
.elgg-river-attachments .elgg-icon {
	float: left;
}
.elgg-river-attachments .elgg-icon-arrow-right {
	margin: 3px 8px 0;
}
.elgg-river-layout .elgg-river-selector {
	float: right;
	margin: 13px 0 18px;
}
.elgg-river-selector * {
	margin-left: 5px;
	vertical-align: middle;
}

.elgg-river-comments {
	position: relative;
}
.elgg-river-comments > li {
	padding: 0px 20px;
	background: #F2F6F9;
}
.elgg-river-comments li .elgg-output {
	padding-right: 5px;
}
.elgg-river-comments .elgg-media {
	padding: 0;
}
.elgg-river-more {
    border-color: #DCDCDC;
    border-style: solid;
    border-width: 0 1px 1px 1px;
	padding: 5px 10px;
	font-size: 85%;
}

.elgg-list-river .elgg-item form {
	padding: 6px;
	height: auto;
}
.elgg-list-river .elgg-item input[type=text] {
	width: 78%;
}
.elgg-list-river .elgg-item input[type=submit] {
	margin: 0 0 0 10px;
}

/* **************************************
	Comments (from elgg_view_comments)
************************************** */
.elgg-comments .elgg-list {
	position: relative;
	margin: 0px;
}
.elgg-comments .elgg-list > li {
    border-color: #DCDCDC;
    border-style: solid;
    border-width: 0 1px 1px 1px;
	padding: 0px 20px;
}
.elgg-comments > form {
	margin-top: 15px;
}

/* Comment highlighting that automatically fades away */
.elgg-comments .elgg-state-highlight {
	-webkit-animation: comment-highlight 5s; /* Chrome, Safari, Opera */
	animation: comment-highlight 5s;
}
.elgg-river-comments .elgg-state-highlight {
	-webkit-animation: comment-highlight 5s; /* Chrome, Safari, Opera */
	animation: comment-highlight-river 5s;
}

/* Chrome, Safari, Opera */
@-webkit-keyframes comment-highlight {
	from {background: #dff2ff;}
	to {background: #FFF;}
}
@-webkit-keyframes comment-highlight-river {
	from {background: #dff2ff;}
	to {background: #F2F6F9;}
}
/* Standard syntax */
@keyframes comment-highlight {
	from {background: #dff2ff;}
	to {background: #FFF;}
}
@keyframes comment-highlight-river {
	from {background: #dff2ff;}
	to {background: #F2F6F9;}
}

/* ***************************************
	Image-related
*************************************** */
.elgg-photo {
	border: 1px solid #DCDCDC;
	padding: 3px;
	background-color: #FFF;

	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;

	max-width: 100%;
	height: auto;
}

<?= elgg_view('elements/components/tags.css', $vars) ?>

@media (max-width: 820px) {
	.elgg-list-river .elgg-item input[type=text] {
		width: 100%;
	}
	.elgg-list-river .elgg-item input[type=submit] {
		margin: 5px 0 0 0;
	}
}