<?php
/**
 * フィーチャーフラグ設定画面
 * WordPressのSettings APIを使用してシンプルな設定画面を作成
 */

// 直接アクセスを防ぐ
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 管理メニューに設定ページを追加
 */
function wp_feature_flag_add_admin_menu() {
	add_options_page(
		'フィーチャーフラグ設定',  // ページタイトル
		'フィーチャーフラグ',      // メニュータイトル
		'manage_options',           // 権限
		'wp-feature-flag',          // スラッグ
		'wp_feature_flag_settings_page' // コールバック関数
	);
}
add_action( 'admin_menu', 'wp_feature_flag_add_admin_menu' );

/**
 * 設定を初期化
 */
function wp_feature_flag_settings_init() {
	// 設定セクションを追加
	add_settings_section(
		'wp_feature_flag_section',           // セクションID
		'利用可能なフィーチャーフラグ',     // セクションタイトル
		'wp_feature_flag_section_callback',  // コールバック関数
		'wp-feature-flag'                    // ページスラッグ
	);
	
	// フローティングボックスの設定フィールドを追加
	add_settings_field(
		'wp_feature_flag_floating_box',           // フィールドID
		'フローティングボックス',                // フィールドタイトル
		'wp_feature_flag_floating_box_callback',  // コールバック関数
		'wp-feature-flag',                        // ページスラッグ
		'wp_feature_flag_section'                 // セクションID
	);
	
	// プロモーションバナーの設定フィールドを追加
	add_settings_field(
		'wp_feature_flag_promo_banner',
		'プロモーションバナー',
		'wp_feature_flag_promo_banner_callback',
		'wp-feature-flag',
		'wp_feature_flag_section'
	);
	
	// デバッグモードの設定フィールドを追加
	add_settings_field(
		'wp_feature_flag_debug_mode',
		'デバッグモード',
		'wp_feature_flag_debug_mode_callback',
		'wp-feature-flag',
		'wp_feature_flag_section'
	);
	
	// 設定を登録
	register_setting( 'wp-feature-flag', 'wp_feature_flag_floating_box' );
	register_setting( 'wp-feature-flag', 'wp_feature_flag_promo_banner' );
	register_setting( 'wp-feature-flag', 'wp_feature_flag_debug_mode' );
}
add_action( 'admin_init', 'wp_feature_flag_settings_init' );

/**
 * セクションの説明を表示
 */
function wp_feature_flag_section_callback() {
	echo '<p>各機能のオン・オフを切り替えることができます。チェックを入れると機能が有効になります。</p>';
	echo '<p>これはフィーチャーフラグと呼ばれる手法で、新機能を段階的にリリースしたり、A/Bテストを行ったりする際に使用されます。</p>';
}

/**
 * フローティングボックスの設定フィールドを表示
 */
function wp_feature_flag_floating_box_callback() {
	$option = get_option( 'wp_feature_flag_floating_box' );
	?>
	<label>
		<input type="checkbox" 
		       name="wp_feature_flag_floating_box" 
		       value="on" 
		       <?php checked( $option, 'on' ); ?>>
		有効にする
	</label>
	<p class="description">
		サイトの右下にフローティングボックスを表示します。訪問者に新機能のお知らせを表示できます。
	</p>
	<?php
}

/**
 * プロモーションバナーの設定フィールドを表示
 */
function wp_feature_flag_promo_banner_callback() {
	$option = get_option( 'wp_feature_flag_promo_banner' );
	?>
	<label>
		<input type="checkbox" 
		       name="wp_feature_flag_promo_banner" 
		       value="on" 
		       <?php checked( $option, 'on' ); ?>>
		有効にする
	</label>
	<p class="description">
		ページ上部にプロモーションバナーを表示します。キャンペーン情報などを告知できます。
	</p>
	<?php
}

/**
 * デバッグモードの設定フィールドを表示
 */
function wp_feature_flag_debug_mode_callback() {
	$option = get_option( 'wp_feature_flag_debug_mode' );
	?>
	<label>
		<input type="checkbox" 
		       name="wp_feature_flag_debug_mode" 
		       value="on" 
		       <?php checked( $option, 'on' ); ?>>
		有効にする
	</label>
	<p class="description">
		HTMLソースにデバッグ情報（メモリ使用量、ページ生成時間）を出力します。開発時のパフォーマンス確認に便利です。
	</p>
	<?php
}

/**
 * 設定ページを表示
 */
function wp_feature_flag_settings_page() {
	// 権限チェック
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	
	// 保存メッセージを表示
	if ( isset( $_GET['settings-updated'] ) ) {
		add_settings_error( 
			'wp_feature_flag_messages',
			'wp_feature_flag_message',
			'設定を保存しました',
			'updated'
		);
	}
	
	// エラー/更新メッセージを表示
	settings_errors( 'wp_feature_flag_messages' );
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		
		<div style="background: #fff; padding: 20px; margin: 20px 0; border-left: 4px solid #0073aa;">
			<h2>📚 フィーチャーフラグとは？</h2>
			<p>フィーチャーフラグ（Feature Flag）は、コードを変更せずに機能のオン・オフを切り替える仕組みです。</p>
			<ul style="list-style-type: disc; margin-left: 20px;">
				<li><strong>段階的リリース：</strong> 新機能を一部のユーザーから徐々に公開</li>
				<li><strong>A/Bテスト：</strong> 異なる機能を比較してユーザーの反応を測定</li>
				<li><strong>緊急停止：</strong> 問題が発生した場合、すぐに機能を無効化</li>
				<li><strong>権限管理：</strong> 特定のユーザーグループにのみ機能を提供</li>
			</ul>
		</div>
		
		<form action="options.php" method="post">
			<?php
			// セキュリティフィールドを出力
			settings_fields( 'wp-feature-flag' );
			
			// 設定セクションを出力
			do_settings_sections( 'wp-feature-flag' );
			
			// 送信ボタンを出力
			submit_button( '設定を保存' );
			?>
		</form>
		
		<div style="background: #f9f9f9; padding: 20px; margin-top: 30px; border: 1px solid #ddd;">
			<h3>💡 使用例</h3>
			<p>このプラグインでは以下の機能をフィーチャーフラグで制御しています：</p>
			<ol>
				<li><strong>フローティングボックス：</strong> wp_footerフックで右下に表示される通知ボックス</li>
				<li><strong>プロモーションバナー：</strong> wp_body_openフックで上部に表示される告知バナー</li>
				<li><strong>デバッグモード：</strong> HTMLコメントとしてパフォーマンス情報を出力</li>
			</ol>
			<p>コード内では <code>wp_feature_flag_is_enabled('flag_name')</code> 関数でフラグの状態をチェックしています。</p>
		</div>
	</div>
	<?php
}