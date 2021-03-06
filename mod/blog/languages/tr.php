<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can handle those during `tx push -s`
 */

return array(
	'item:object:blog' => 'Bloglar',
	'collection:object:blog' => 'Bloglar',
	'collection:object:blog:all' => 'Tüm site blogları',
	'collection:object:blog:owner' => '%s kullanıcısının blogları',
	'collection:object:blog:group' => 'Group blogs',
	'collection:object:blog:friends' => 'Arkadaşların blogları',
	'add:object:blog' => 'Blog gönderisi ekle',
	'edit:object:blog' => 'Blog gönderisini düzenle',

	'blog:revisions' => 'Düzeltmeler',
	'blog:archives' => 'Arşivler',

	'groups:tool:blog' => 'Grup blogunu etkinleştir',
	'blog:write' => 'Bir blog gönderisi yaz',

	// Editing
	'blog:excerpt' => 'Alıntı',
	'blog:body' => 'Gövde',
	'blog:save_status' => 'Son kayıt:',

	'blog:revision' => 'Düzeltme',
	'blog:auto_saved_revision' => 'Otomatik Kaydedilmiş Düzeltme',

	// messages
	'blog:message:saved' => 'Blog gönderisi kaydedildi.',
	'blog:error:cannot_save' => 'Blog gönderisi kaydedilemedi.',
	'blog:error:cannot_auto_save' => 'Blog gönderisi otomatik olarak kaydedilemedi.',
	'blog:error:cannot_write_to_container' => 'Gruba blog kaydedebilmek için yetkisiz erişim.',
	'blog:messages:warning:draft' => 'Bu gönderinin kaydedilmemiş bir taslağı var!',
	'blog:edit_revision_notice' => '(Eski sürüm)',
	'blog:message:deleted_post' => 'Blog gönderisi silindi.',
	'blog:error:cannot_delete_post' => 'Blog gönderisi silinemedi.',
	'blog:none' => 'Blog gönderisi yok',
	'blog:error:missing:title' => 'Lütfen bir blog başlığı girin!',
	'blog:error:missing:description' => 'Lütfen blogunuzun gövdesini girin!',
	'blog:error:cannot_edit_post' => 'Bu gönderi mevcut olmayabilir veya düzenlemek için izniniz olmayabilir.',
	'blog:error:post_not_found' => 'Belirtilen blog gönderisi bulunamadı.',
	'blog:error:revision_not_found' => 'Bu düzeltme bulunamadı.',

	// river
	'river:object:blog:create' => '%s published a blog post %s',
	'river:object:blog:comment' => '%s commented on the blog %s',

	// notifications
	'blog:notify:summary' => '%s adında yeni bir blog gönderisi',
	'blog:notify:subject' => 'Yeni blog gönderisi: %s',
	'blog:notify:body' =>
'
%s published a new blog post: %s

%s

View and comment on the blog post:
%s
',

	// widget
	'widgets:blog:name' => 'Blog posts',
	'widgets:blog:description' => 'Son blog gönderilerinizi göster',
	'blog:moreblogs' => 'Daha fazla blog gönderisi',
	'blog:numbertodisplay' => 'Gösterilecek blog gönderisi sayısı',
);
