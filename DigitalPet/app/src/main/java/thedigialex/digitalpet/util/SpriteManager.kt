package thedigialex.digitalpet.util

import android.graphics.Bitmap
import android.graphics.drawable.Drawable
import android.widget.ImageView
import com.bumptech.glide.Glide
import com.bumptech.glide.request.target.CustomTarget

object SpriteManager {
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

    fun setUpImageSprite(imageView: ImageView, spriteSheet: String, tilesPerRow: Int) {
        val baseUrl = "http://10.0.2.2:8000/"
        val imageUrl = baseUrl + spriteSheet.replace("public/", "storage/")
        Glide.with(imageView.context)
            .asBitmap()
            .load(imageUrl)
            .into(object : CustomTarget<Bitmap>() {
                override fun onResourceReady(resource: Bitmap, transition: com.bumptech.glide.request.transition.Transition<in Bitmap>?) {
                    val sprites = splitSpriteSheet(resource, tilesPerRow)
                    if (sprites.isNotEmpty()) {
                        imageView.setImageBitmap(sprites[0])
                        var currentIndex = 0
                        imageView.setOnClickListener {
                            currentIndex = (currentIndex + 1) % sprites.size
                            imageView.setImageBitmap(sprites[currentIndex])
                        }
                    }
                }
                override fun onLoadCleared(placeholder: Drawable?) {}
            })
    }
}
