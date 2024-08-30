package thedigialex.digitalpet.model.entities

import android.content.Context
import android.graphics.Bitmap
import android.widget.ImageView
import thedigialex.digitalpet.util.SpriteManager

data class DigitalMonster(
    val id: Int,
    val eggId: Int,
    val monsterId: Int,
    val spriteSheet: String,
    val stage: String,
    val minWeight: Int,
    val maxEnergy: Int,
    val requiredEvoPoints: Int
) {
    var sprites: List<Bitmap>? = null

    fun setupSprite(context: Context, onSpritesReady: () -> Unit = {}) {
        sprites = SpriteManager.setupSprite(context, this) {
            onSpritesReady()
        }
    }

    fun animation(imageView: ImageView, animationType: Int) {
        if (!sprites.isNullOrEmpty()) {
            when (animationType) {
                1 -> SpriteManager.animateSprite(imageView, sprites!!, listOf(0, 1))    // Walk - Idle animation
                2 -> SpriteManager.animateSprite(imageView, sprites!!, listOf(0, 2))    // Eat animation
                3 -> SpriteManager.animateSprite(imageView, sprites!!, listOf(3, 4))    // Battle animation
                4 -> SpriteManager.animateSprite(imageView, sprites!!, listOf(5, 6))    // Tired animation
                5 -> SpriteManager.animateSprite(imageView, sprites!!, listOf(7, 8))    // Sad animation
                6 -> {
                    val flippedImage = SpriteManager.flipBitmap(sprites!![9])
                    sprites = sprites!! + flippedImage
                    SpriteManager.animateSprite(imageView, sprites!!, listOf(9, sprites!!.size - 1)) // Deny animation
                }
                7 -> SpriteManager.animateSprite(imageView, sprites!!, listOf(0, 10))   // Happy animation
            }
        }
    }
}
