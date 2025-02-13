package thedigialex.digitalpet.model.entities

import android.content.Context
import android.graphics.Bitmap
import android.widget.ImageView
import com.google.gson.annotations.SerializedName
import thedigialex.digitalpet.util.SpriteManager

data class DigitalMonster(
    val id: Int,
    val name: String,
    @SerializedName("egg_group_id") val eggGroupId: Int,
    @SerializedName("sprite_image_0") val spriteImage0: String?,
    @SerializedName("element_0") val element0: String?,
    @SerializedName("sprite_image_1") val spriteImage1: String?,
    @SerializedName("element_1") val element1: String?,
    @SerializedName("sprite_image_2") val spriteImage2: String?,
    @SerializedName("element_2") val element2: String?,
    val stage: String,
    @SerializedName("required_evo_points") val requiredEvoPoints: Int
){
    var sprites: List<Bitmap>? = null

    fun setupSprite(context: Context, type: String, onSpritesReady: () -> Unit = {}) {
        sprites = SpriteManager.setupSprite(context, this, type) {
            onSpritesReady()
        }
    }

    fun animation(imageView: ImageView, animationType: Int) {
        SpriteManager.stopAnimation()
        if (!sprites.isNullOrEmpty()) {
            when (animationType) {
                1 -> SpriteManager.animateSprite(imageView, sprites!!, listOf(0, 1, 0, 1, 2, 1))    // Walk - Idle animation
                2 -> SpriteManager.animateSprite(imageView, sprites!!, listOf(0, 2))    // Eat animation
                3 -> SpriteManager.animateSprite(imageView, sprites!!, listOf(3, 4))    // Battle animation
                4 -> SpriteManager.animateSprite(imageView, sprites!!, listOf(5, 6))    // Tired animation
                5 -> SpriteManager.animateSprite(imageView, sprites!!, listOf(0, 7))    // Sad animation
                6 -> SpriteManager.animateSprite(imageView, sprites!!, listOf(0, 8))   // Happy animation
                7 -> {
                    val flippedImage = SpriteManager.flipBitmap(sprites!![9])
                    sprites = sprites!! + flippedImage
                    SpriteManager.animateSprite(imageView, sprites!!, listOf(9, sprites!!.size - 1)) // Deny animation
                }
            }
        }
    }

    fun sideAnimation(imageView: ImageView, animationType: Int) {
        SpriteManager.stopSideAnimation()
        if (!sprites.isNullOrEmpty()) {
            when (animationType) {
                1 -> SpriteManager.animateSideSprite(imageView, sprites!!, listOf(0, 1, 0, 1, 2, 1))    // Walk - Idle animation
                2 -> SpriteManager.animateSideSprite(imageView, sprites!!, listOf(0, 2))    // Eat animation
                3 -> SpriteManager.animateSideSprite(imageView, sprites!!, listOf(3, 4))    // Battle animation
                4 -> SpriteManager.animateSideSprite(imageView, sprites!!, listOf(5, 6))    // Tired animation
                5 -> SpriteManager.animateSideSprite(imageView, sprites!!, listOf(0, 7))    // Sad animation
                6 -> SpriteManager.animateSideSprite(imageView, sprites!!, listOf(0, 8))   // Happy animation
                7 -> {
                    val flippedImage = SpriteManager.flipBitmap(sprites!![9])
                    sprites = sprites!! + flippedImage
                    SpriteManager.animateSideSprite(imageView, sprites!!, listOf(9, sprites!!.size - 1)) // Deny animation
                }
            }
        }
    }
}
