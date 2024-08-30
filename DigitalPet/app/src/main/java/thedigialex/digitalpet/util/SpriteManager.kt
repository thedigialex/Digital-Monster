package thedigialex.digitalpet.util

import android.content.Context
import android.graphics.Bitmap
import android.graphics.Matrix
import android.graphics.drawable.Drawable
import android.os.Handler
import android.os.Looper
import android.widget.ImageView
import com.bumptech.glide.Glide
import com.bumptech.glide.request.target.CustomTarget
import thedigialex.digitalpet.model.entities.DigitalMonster

object SpriteManager {

    fun setupSprite(context: Context, digitalMonster: DigitalMonster, onSpritesReady: () -> Unit = {}): List<Bitmap>? {
        val tilesPerRow = if (digitalMonster.stage == "Egg") 2 else 11
        val baseUrl = context.getString(thedigialex.digitalpet.R.string.base_url)
        val imageUrl = baseUrl + digitalMonster.spriteSheet.replace("public/", "storage/")

        var sprites: List<Bitmap>? = null

        Glide.with(context)
            .asBitmap()
            .load(imageUrl)
            .into(object : CustomTarget<Bitmap>() {
                override fun onResourceReady(resource: Bitmap, transition: com.bumptech.glide.request.transition.Transition<in Bitmap>?) {
                    sprites = splitSpriteSheet(resource, tilesPerRow)
                    digitalMonster.sprites = sprites
                    onSpritesReady()
                }
                override fun onLoadCleared(placeholder: Drawable?) {}
            })

        return sprites
    }

    fun splitSpriteSheet(spriteSheet: Bitmap, tilesPerRow: Int): List<Bitmap> {
        val tileWidth = spriteSheet.width / tilesPerRow
        val tiles = mutableListOf<Bitmap>()
        for (x in 0 until tilesPerRow) {
            val tile = Bitmap.createBitmap(
                spriteSheet,
                x * tileWidth,
                0,
                tileWidth,
                spriteSheet.height
            )
            tiles.add(tile)
        }
        return tiles
    }

    fun animateSprite(imageView: ImageView, sprites: List<Bitmap>, frames: List<Int>, interval: Long = 500) {
        val handler = Handler(Looper.getMainLooper())
        var currentIndex = 0

        val runnable = object : Runnable {
            override fun run() {
                imageView.setImageBitmap(sprites[frames[currentIndex]])
                currentIndex = (currentIndex + 1) % frames.size
                handler.postDelayed(this, interval)
            }
        }
        handler.post(runnable)
    }

    fun flipBitmap(original: Bitmap): Bitmap {
        val matrix = Matrix().apply {
            preScale(-1.0f, 1.0f)
        }
        return Bitmap.createBitmap(original, 0, 0, original.width, original.height, matrix, true)
    }
}
