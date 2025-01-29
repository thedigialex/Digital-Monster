package thedigialex.digitalpet.model.entities

import android.content.Context
import android.graphics.Bitmap
import android.widget.ImageView
import thedigialex.digitalpet.util.SpriteManager

data class TrainingEquipment(
    val id: Int,
    val name: String,
    val stat: String,
    val image: String,
) {
    var sprites: List<Bitmap>? = null

    fun setupSprite(context: Context, onSpritesReady: () -> Unit = {}) {
        sprites = SpriteManager.setupTrainingEquipmentSprite(context, this) {
            onSpritesReady()
        }
    }

    fun animation(imageView: ImageView) {
        stopAnimation()
        if (!sprites.isNullOrEmpty()) {
            SpriteManager.animateSideSprite(imageView, sprites!!, listOf(0, 1, 2, 3))
        }
    }

    fun stopAnimation() {
        SpriteManager.stopSideAnimation()
    }
}
