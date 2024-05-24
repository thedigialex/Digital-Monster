package thedigialex.digitalpet.ui

import android.content.Context
import android.os.Bundle
import android.widget.Toast
import androidx.activity.ComponentActivity
import androidx.activity.enableEdgeToEdge
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import thedigialex.digitalpet.model.entities.Inventory
import thedigialex.digitalpet.model.requests.NicknameRequest
import thedigialex.digitalpet.model.entities.User
import thedigialex.digitalpet.network.RetrofitInstance


class MainActivity : ComponentActivity() {
    private lateinit var inventoryList: List<Inventory>
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        //updateNickname("testing")
        val user = getUserData()


        user.name.let {
            Toast.makeText(applicationContext, "Welcome back, $it!", Toast.LENGTH_LONG).show()
        }
        fetchUserInventories()
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
    private fun fetchUserInventories() {
        CoroutineScope(Dispatchers.IO).launch {
            val api = RetrofitInstance.getApi(applicationContext)
            val response = api.getUserInventories()
            runOnUiThread {
                if (response.isSuccessful) {
                    response.body()?.let { inventoryResponse ->
                        inventoryList = inventoryResponse.inventories
                        Toast.makeText(applicationContext, "Inventories fetched successfully", Toast.LENGTH_LONG).show()
                    }
                } else {
                    Toast.makeText(applicationContext, "Failed to fetch inventories", Toast.LENGTH_LONG).show()
                }
            }
        }
    }

}
