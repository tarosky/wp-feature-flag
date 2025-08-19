/**
 * WP Feature Flag JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
	// フローティングボックスの閉じるボタン
	const closeButton = document.getElementById('wp-feature-flag-close');
	const floatingBox = document.getElementById('wp-feature-flag-floating-box');
	
	if (closeButton && floatingBox) {
		closeButton.addEventListener('click', function() {
			// フェードアウトアニメーションを追加
			floatingBox.classList.add('wp-feature-flag-hidden');
			
			// アニメーション完了後に要素を削除
			setTimeout(function() {
				floatingBox.style.display = 'none';
			}, 300);
			
			// ローカルストレージに閉じた状態を保存（オプション）
			localStorage.setItem('wp_feature_flag_box_closed', 'true');
		});
		
		// 既に閉じられている場合は表示しない
		if (localStorage.getItem('wp_feature_flag_box_closed') === 'true') {
			floatingBox.style.display = 'none';
		}
	}
	
	// 5秒後に自動的に小さくする（オプション）
	setTimeout(function() {
		if (floatingBox && floatingBox.style.display !== 'none') {
			floatingBox.style.transform = 'scale(0.9)';
			floatingBox.style.transition = 'transform 0.3s ease';
		}
	}, 5000);
});