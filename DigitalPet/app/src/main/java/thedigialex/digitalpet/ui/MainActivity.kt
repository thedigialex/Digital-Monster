package thedigialex.digitalpet.ui

import android.content.ContentValues
import android.content.Context
import android.os.Bundle
import android.os.Environment
import android.provider.MediaStore
import android.widget.Button
import android.widget.ImageView
import android.widget.Toast
import androidx.activity.ComponentActivity
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import thedigialex.digitalpet.R
import thedigialex.digitalpet.controller.CaseController
import thedigialex.digitalpet.model.entities.User
import thedigialex.digitalpet.model.requests.NicknameRequest
import thedigialex.digitalpet.network.RetrofitInstance
import thedigialex.digitalpet.services.FetchService


class MainActivity : ComponentActivity() {
    private lateinit var fetchService: FetchService
    private lateinit var caseController: CaseController;

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        createController()
        findViewById<Button>(R.id.switchButton).setOnClickListener { caseController.switchMenu() }

        //updateNickname("testing")
        /*
        val user = getUserData()
        fetchService = FetchService(this)
        lifecycleScope.launch {
            val userDigitalMonsterList = fetchService.fetchUserDigitalMonsters(isMain = true)
            if (userDigitalMonsterList.isNotEmpty()) {
                val mainMonster = userDigitalMonsterList.first()
                Log.d("DigitalMonster", mainMonster.toString())
                mainMonster.digital_monster.let { digitalMonster ->
                    var tilesPerRow = 11
                    if (digitalMonster.eggId == 0) {
                        tilesPerRow = 8
                    }
                    val imageView = findViewById<ImageView>(R.id.mainImageView)
                    SpriteManager.setUpImageSprite(imageView, digitalMonster.spriteSheet, tilesPerRow)
                }
            }
            else {
                val digitalMonster = fetchService.fetchDigitalMonster(eggId = 1, monsterId = 2)
                digitalMonster?.let {
                    var tilesPerRow = 11
                    if(it.eggId == 0) {
                        tilesPerRow = 8
                    }
                    val imageView = findViewById<ImageView>(R.id.mainImageView)
                    SpriteManager.setUpImageSprite(imageView, it.spriteSheet, tilesPerRow)
                }
            }

            val inventoryList = fetchService.fetchUserInventory(isEquipped = true)

            if (inventoryList.isNotEmpty()) {
                inventoryList.forEach { inventory ->
                    Log.d("InventoryItem", inventory.item.name)
                    Log.d("DigitalMonster", inventory.userId.toString())
                    Log.d("DigitalMonster", inventory.toString())
                }
            } else {
                Toast.makeText(this@MainActivity, "No Inventories found", Toast.LENGTH_SHORT).show()
            }


        }
        */
    }

    private fun createController() {
        val buttons = arrayOf<Button>(
            findViewById(R.id.upButton),
            findViewById(R.id.bottomButton),
            findViewById(R.id.leftButton),
            findViewById(R.id.rightButton)
        )
        val images = arrayOf<ImageView>(
            findViewById(R.id.topMenu_0),
            findViewById(R.id.topMenu_1),
            findViewById(R.id.topMenu_2),
            findViewById(R.id.topMenu_3),
            findViewById(R.id.bottomMenu_0),
            findViewById(R.id.bottomMenu_1),
            findViewById(R.id.bottomMenu_2),
            findViewById(R.id.bottomMenu_3),
        )

        caseController = CaseController(findViewById(R.id.parentMenuLayout),buttons, images)
    }

    private fun updateNickname(nickname: String) {
        CoroutineScope(Dispatchers.IO).launch {
            val api = RetrofitInstance.getApi(applicationContext)
            val nicknameRequest = NicknameRequest(nickname)
            val response = api.updateNickname(nicknameRequest)
            runOnUiThread {
                if (response.isSuccessful) {
                    Toast.makeText(applicationContext, "Nickname updated successfully", Toast.LENGTH_LONG).show()
                } else {
                    Toast.makeText(applicationContext, "Failed to update nickname", Toast.LENGTH_LONG).show()
                }
            }
        }
    }
    private fun getUserData(): User {
        val sharedPreferences = getSharedPreferences("UserData", Context.MODE_PRIVATE)
        return User(
            id = sharedPreferences.getLong("userId", -1),
            name = sharedPreferences.getString("userName", "")!!,
            email = sharedPreferences.getString("userEmail", "")!!,
            nickname = sharedPreferences.getString("userNickname", null)
        )
    }

    private fun saveImageToMediaStore(context: Context, imageUrl: String, fileName: String, callback: (Boolean) -> Unit) {
        CoroutineScope(Dispatchers.IO).launch {
            try {
                val api = RetrofitInstance.getApi(context)
                val response = api.downloadImage(imageUrl)

                if (response.isSuccessful) {
                    response.body()?.let { body ->
                        val contentResolver = context.contentResolver
                        val contentValues = ContentValues().apply {
                            put(MediaStore.MediaColumns.DISPLAY_NAME, fileName)
                            put(MediaStore.MediaColumns.MIME_TYPE, "image/png")
                            put(MediaStore.MediaColumns.RELATIVE_PATH, Environment.DIRECTORY_PICTURES)
                        }

                        val uri = contentResolver.insert(MediaStore.Images.Media.EXTERNAL_CONTENT_URI, contentValues)

                        uri?.let {
                            contentResolver.openOutputStream(uri)?.use { outputStream ->
                                body.byteStream().use { inputStream ->
                                    inputStream.copyTo(outputStream)
                                }
                            }

                            runOnUiThread {
                                callback(true)
                            }
                        } ?: run {
                            runOnUiThread {
                                callback(false)
                            }
                        }
                    } ?: run {
                        runOnUiThread {
                            callback(false)
                        }
                    }
                } else {
                    runOnUiThread {
                        callback(false)
                    }
                }
            } catch (e: Exception) {
                e.printStackTrace()
                runOnUiThread {
                    callback(false)
                }
            }
        }
    }
}
