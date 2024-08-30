package thedigialex.digitalpet.ui

import android.graphics.Bitmap
import android.os.Bundle
import android.widget.Button
import android.widget.ImageView
import androidx.activity.ComponentActivity
import androidx.lifecycle.lifecycleScope
import thedigialex.digitalpet.R
import thedigialex.digitalpet.controller.CaseController
import thedigialex.digitalpet.controller.UserController


class MainActivity : ComponentActivity() {
    private lateinit var caseController: CaseController
    private lateinit var userController: UserController

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)
        createController()
    }

    private fun createController() {
        findViewById<Button>(R.id.switchButton).setOnClickListener { caseController.switchMenu() }
        val buttons = arrayOf<Button>(
            findViewById(R.id.upButton),
            findViewById(R.id.bottomButton),
            findViewById(R.id.leftButton),
            findViewById(R.id.rightButton)
        )
        val menus = arrayOf<ImageView>(
            findViewById(R.id.topMenu_0),
            findViewById(R.id.topMenu_1),
            findViewById(R.id.topMenu_2),
            findViewById(R.id.topMenu_3),
            findViewById(R.id.bottomMenu_0),
            findViewById(R.id.bottomMenu_1),
            findViewById(R.id.bottomMenu_2),
            findViewById(R.id.bottomMenu_3),
        )
        caseController = CaseController(findViewById(R.id.parentMenuLayout), buttons, menus)
        userController = UserController(this, lifecycleScope)
        userController.getUserDigitalMonster { mainDigitalMonster ->
            if (mainDigitalMonster == null) {
                userController.getDigitalMonsters(eggReturn = true) { digitalMonsters ->
                    if (!digitalMonsters.isNullOrEmpty()) {
                        val allSprites = mutableListOf<Bitmap>()
                        digitalMonsters.forEach { monster ->
                            monster.sprites?.firstOrNull()?.let { firstSprite ->
                                allSprites.add(firstSprite)
                            }
                        }
                        caseController.imageResources = allSprites;
                        caseController.menuId = -10
                        caseController.isMenuOpen = true
                        caseController.updateMenuLayout()
                    }
                }
            } else {
                val imageView = findViewById<ImageView>(R.id.mainImageView)
                mainDigitalMonster.setupSprite(this) {
                    mainDigitalMonster.animation(imageView, 1)
                }
            }
        }
    }
}
