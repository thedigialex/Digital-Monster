package thedigialex.digitalpet.ui

import android.content.ContentValues
import android.content.Context
import android.os.Bundle
import android.os.Environment
import android.provider.MediaStore
import android.util.Log
import android.widget.Toast
import androidx.activity.ComponentActivity
import androidx.lifecycle.lifecycleScope
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import thedigialex.digitalpet.R
import thedigialex.digitalpet.model.entities.Inventory
import thedigialex.digitalpet.model.entities.User
import thedigialex.digitalpet.model.entities.UserDigitalMonster
import thedigialex.digitalpet.model.requests.NicknameRequest
import thedigialex.digitalpet.network.RetrofitInstance
import thedigialex.digitalpet.services.FetchService


class MainActivity : ComponentActivity() {
    private lateinit var inventoryList: List<Inventory>
    private lateinit var userDigitalMonsterList: List<UserDigitalMonster>

    private lateinit var fetchService: FetchService
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)
        //updateNickname("testing")
        val user = getUserData()

        user.name.let {
            Toast.makeText(applicationContext, "Welcome back, $it!", Toast.LENGTH_LONG).show()
        }

        fetchService = FetchService(this)

        lifecycleScope.launch {
            val userDigitalMonsterList = fetchService.fetchUserDigitalMonsters(isMain = true)
            if (userDigitalMonsterList.isNotEmpty()) {
                userDigitalMonsterList.forEach { monster ->
                    Log.d("DigitalMonster", monster.name)
                }
                Toast.makeText(this@MainActivity, "Digital Monsters fetched successfully", Toast.LENGTH_SHORT).show()
            } else {
                Toast.makeText(this@MainActivity, "No Digital Monsters found", Toast.LENGTH_SHORT).show()
            }

            val inventoryList = fetchService.fetchUserInventory(isEquipped = true)
            if (inventoryList.isNotEmpty()) {
                inventoryList.forEach { inventory ->
                    Log.d("InventoryItem", inventory.item.name)
                }
                Toast.makeText(this@MainActivity, "Inventories fetched successfully", Toast.LENGTH_SHORT).show()
            } else {
                Toast.makeText(this@MainActivity, "No Inventories found", Toast.LENGTH_SHORT).show()
            }

            val digitalMonster = fetchService.fetchDigitalMonster(eggId = 0, monsterId = 0)
            digitalMonster?.let {
                Log.d("DigitalMonster", "Max Weight: ${it.minWeight}")
            } ?: run {
                Log.e("DigitalMonster", "No digital monster found for the given eggId and monsterId")
            }
        }
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
