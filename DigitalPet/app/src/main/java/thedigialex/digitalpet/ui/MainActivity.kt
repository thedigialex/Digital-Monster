package thedigialex.digitalpet.ui

import android.content.Context
import android.os.Bundle
import android.widget.Button
import android.widget.ImageView
import androidx.activity.ComponentActivity
import androidx.lifecycle.lifecycleScope
import thedigialex.digitalpet.R
import thedigialex.digitalpet.controller.CaseController
import thedigialex.digitalpet.controller.UserController
import thedigialex.digitalpet.model.entities.User


class MainActivity : ComponentActivity() {
    private lateinit var caseController: CaseController
    private lateinit var userController: UserController
    private lateinit var user: User

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)
        val sharedPreferences = this.getSharedPreferences("UserData", Context.MODE_PRIVATE)
        user = User(
            id = sharedPreferences.getLong("userId", -1),
            name = sharedPreferences.getString("userName", "")!!,
            email = sharedPreferences.getString("userEmail", "")!!,
            bits = sharedPreferences.getInt("userBits", 0),
            userDigitalMonsters = null,
            mainDigitalMonster = null,
            inventory = null
        )
        createController()
    }

    private fun createController() {
        val buttons = arrayOf<Button>(
            findViewById(R.id.upButton),
            findViewById(R.id.bottomButton),
            findViewById(R.id.leftButton),
            findViewById(R.id.rightButton),
            findViewById(R.id.switchButton),
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
        caseController = CaseController(this, lifecycleScope, findViewById(R.id.parentMenuLayout), buttons, menus, user)
        userController = UserController(this, lifecycleScope, user)
        userController.getUserDigitalMonster {
            val imageView = findViewById<ImageView>(R.id.mainImageView)
            if (user.mainDigitalMonster != null) {

                user.mainDigitalMonster?.digital_monster?.animation(imageView, 1)
                //example on how to update
                //user.mainDigitalMonster?.name =  "post"
                //userController.updateUserDigitalMonster()
                //Log.d("testing", user.mainDigitalMonster.toString())
            } else {
                userController.getDigitalMonsters(eggReturn = true) { digitalMonsters ->
                    if (digitalMonsters != null) {
                        caseController.setUpNewUserDigitalMonster(digitalMonsters, imageView)
                    }
                }
            }
        }
    }
}
