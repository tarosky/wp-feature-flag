<?php
/**
 * Plugin Name: WP Feature Flag
 * Plugin URI: https://github.com/tarosky/wp-feature-flag
 * Description: フィーチャーフラグを使用して機能のオン・オフを切り替えるシンプルなプラグイン
 * Version: 1.0.0
 * Author: Tarosky
 * Author URI: https://tarosky.co.jp
 * Text Domain: wp-feature-flag
 */

// 直接アクセスを防ぐ
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// プラグインのパスを定義
define( 'WP_FEATURE_FLAG_DIR', plugin_dir_path( __FILE__ ) );
define( 'WP_FEATURE_FLAG_URL', plugin_dir_url( __FILE__ ) );

// 設定画面を読み込む
require_once WP_FEATURE_FLAG_DIR . 'includes/flag-settings.php';

/**
 * フィーチャーフラグが有効かどうかをチェックする関数
 *
 * @param string $flag_name フラグの名前
 * @return bool 有効な場合はtrue
 */
function wp_feature_flag_is_enabled( $flag_name ) {
	// WordPressのオプションから設定値を取得
	$option = get_option( 'wp_feature_flag_' . $flag_name, false );
	return $option === 'on';
}

/**
 * フローティングボックスをフッターに表示する
 */
function wp_feature_flag_add_floating_box() {
	// フィーチャーフラグをチェック
	if ( ! wp_feature_flag_is_enabled( 'floating_box' ) ) {
		return;
	}

	// 管理画面では表示しない
	if ( is_admin() ) {
		return;
	}

	?>
	<div id="wp-feature-flag-floating-box">
		<button id="wp-feature-flag-close">&times;</button>
		<h3>🎉 新機能のお知らせ</h3>
		<p>このサイトでは新しい機能をテスト中です！</p>
		<p><small>フィーチャーフラグで制御されています</small></p>
	</div>
	<?php
}
add_action( 'wp_footer', 'wp_feature_flag_add_floating_box' );

/**
 * CSSを読み込む
 */
function wp_feature_flag_enqueue_styles() {
	// フィーチャーフラグをチェック
	if ( ! wp_feature_flag_is_enabled( 'floating_box' ) ) {
		return;
	}

	// 管理画面では読み込まない
	if ( is_admin() ) {
		return;
	}

	wp_enqueue_style(
		'wp-feature-flag-style',
		WP_FEATURE_FLAG_URL . 'assets/style.css',
		array(),
		'1.0.0'
	);

	wp_enqueue_script(
		'wp-feature-flag-script',
		WP_FEATURE_FLAG_URL . 'assets/script.js',
		array(),
		'1.0.0',
		true
	);
}
add_action( 'wp_enqueue_scripts', 'wp_feature_flag_enqueue_styles' );

/**
 * プロモーションバナーを表示する（2つ目のフィーチャーフラグの例）
 */
function wp_feature_flag_add_promo_banner() {
	// フィーチャーフラグをチェック
	if ( ! wp_feature_flag_is_enabled( 'promo_banner' ) ) {
		return;
	}

	?>
	<div id="wp-feature-flag-promo-banner">
		<p>🎁 期間限定キャンペーン実施中！ 全商品20%オフ！</p>
	</div>
	<?php
}
add_action( 'wp_body_open', 'wp_feature_flag_add_promo_banner' );

/**
 * デバッグモードを有効にする（3つ目のフィーチャーフラグの例）
 */
function wp_feature_flag_debug_mode() {
	// フィーチャーフラグをチェック
	if ( ! wp_feature_flag_is_enabled( 'debug_mode' ) ) {
		return;
	}

	// HTMLコメントでデバッグ情報を出力
	echo "\n<!-- Debug Mode: ON -->\n";
	echo "<!-- Memory Usage: " . memory_get_usage() . " bytes -->\n";
	echo "<!-- Page Generation Time: " . timer_stop(0) . " seconds -->\n";
}
add_action( 'wp_footer', 'wp_feature_flag_debug_mode', 100 );
