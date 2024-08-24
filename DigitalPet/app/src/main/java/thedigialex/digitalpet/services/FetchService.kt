package thedigialex.digitalpet.services

import android.content.Context
import android.util.Log
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.withContext
import thedigialex.digitalpet.model.entities.DigitalMonster
import thedigialex.digitalpet.model.entities.Inventory
import thedigialex.digitalpet.model.entities.UserDigitalMonster
import thedigialex.digitalpet.network.RetrofitInstance

class FetchService(private val context: Context) {
    suspend fun fetchUserDigitalMonsters(isMain: Boolean? = null): List<UserDigitalMonster> {
        return withContext(Dispatchers.IO) {
            val api = RetrofitInstance.getApi(context)
            val response = api.getUserDigitalMonsters(isMain)
            if (response.isSuccessful) {
                val responseBody = response.body()
                Log.d("API", response.body().toString())
                responseBody?.let { userDigitalMonsterResponse ->
                    val userDigitalMonsterList = userDigitalMonsterResponse.userDigitalMonsters

                    // Logging each UserDigitalMonster in the list
                    userDigitalMonsterList.forEach { userDigitalMonster ->
                        Log.d("UserDigitalMonster", "ID: ${userDigitalMonster.digitalMonsterId}, Name: ${userDigitalMonster.name}")
                    }

                    userDigitalMonsterList
                } ?: run {
                    Log.d("UserDigitalMonster", "No data received. Response body is null.")
                    emptyList()
                }

            } else {
                Log.e("FetchService", "Failed to fetch digital monsters. Response code: ${response.code()}")
                emptyList()
            }
        }
    }
    suspend fun fetchUserInventory(isEquipped: Boolean? = null): List<Inventory> {
        return withContext(Dispatchers.IO) {
            val api = RetrofitInstance.getApi(context)
            val response = api.getUserInventory(isEquipped)
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
    suspend fun fetchDigitalMonster(eggId: Int? = 0, monsterId: Int? = 0): DigitalMonster? {
        return withContext(Dispatchers.IO) {
            val api = RetrofitInstance.getApi(context)
            val response = api.fetchDigitalMonster(eggId ?: 0, monsterId ?: 0)
            if (response.isSuccessful) {
                response.body()?.digitalMonster
            } else {
                Log.e("FetchService", "Failed to fetch Digital Monster. Response code: ${response.code()}")
                null
            }
        }
    }
}
