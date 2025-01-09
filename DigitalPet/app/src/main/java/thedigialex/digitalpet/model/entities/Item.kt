package thedigialex.digitalpet.model.entities

import android.content.Context
import android.graphics.Bitmap
import android.widget.ImageView
import thedigialex.digitalpet.util.SpriteManager

data class Item(
    val id: Int,
    val name: String,
    val type: String,
    val effect: String,
    val price: Int,
    val rarity: String,
    val isAvailable: Int,
    val image: String
) {
    var sprites: List<Bitmap>? = null

    fun setupSprite(context: Context, onSpritesReady: () -> Unit = {}) {
        sprites = SpriteManager.setupItemSprite(context, this) {
            onSpritesReady()
        }
    }

    fun animation(imageView: ImageView) {
        SpriteManager.stopAnimation()
        if (!sprites.isNullOrEmpty()) {
            SpriteManager.animateSideSprite(imageView, sprites!!, listOf(0, 1, 2, 3, 3))
        }
    }
}