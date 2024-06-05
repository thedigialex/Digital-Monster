package thedigialex.digitalpet.services

import android.content.Context
import android.util.Log
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.withContext
import thedigialex.digitalpet.model.entities.Inventory
import thedigialex.digitalpet.model.entities.UserDigitalMonster
import thedigialex.digitalpet.network.RetrofitInstance

class FetchService(private val context: Context) {
    suspend fun fetchAllUserDigitalMonsters(): List<UserDigitalMonster> {
        return withContext(Dispatchers.IO) {
            val api = RetrofitInstance.getApi(context)
            val response = api.getUserDigitalMonsters()
            if (response.isSuccessful) {
                val responseBody = response.body()
                responseBody?.let { userDigitalMonsterResponse ->
                    val userDigitalMonsterList = userDigitalMonsterResponse.userDigitalMonsters
                    userDigitalMonsterList
                } ?: emptyList()
            } else {
                Log.e("FetchService", "Failed to fetch digital monsters. Response code: ${response.code()}")
                emptyList()
            }
        }
    }
    suspend fun fetchUserInventory(): List<Inventory> {
        return withContext(Dispatchers.IO) {
            val api = RetrofitInstance.getApi(context)
            val response = api.getUserInventory()
            if (response.isSuccessful) {
                val responseBody = response.body()
                responseBody?.let { inventoryResponse ->
                    val inventory = inventoryResponse.inventory
                    inventory
                } ?: emptyList()
            } else {
                Log.e("FetchService", "Failed to fetch Inventory. Response code: ${response.code()}")
                emptyList()
            }
        }
    }
}
