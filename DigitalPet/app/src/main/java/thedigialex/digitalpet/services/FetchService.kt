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

    suspend fun fetchUserDigitalMonsters(): UserDigitalMonster? {
        return withContext(Dispatchers.IO) {
            val api = RetrofitInstance.getApi(context)
            val response = api.getUserDigitalMonster()
            if (response.isSuccessful) {
                val responseBody = response.body()
                Log.d("API", response.body().toString())
                responseBody?.let { userDigitalMonsterResponse ->
                    val userDigitalMonster = userDigitalMonsterResponse.userDigitalMonster
                    Log.d("UserDigitalMonster", "ID: ${userDigitalMonster.digitalMonsterId}, Name: ${userDigitalMonster.name}")
                    userDigitalMonster
                } ?: run {
                    Log.d("UserDigitalMonster", "No data received. Response body is null.")
                    null
                }
            } else {
                Log.e("FetchService", "Failed to fetch user digital monster. Response code: ${response.code()}")
                null
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

    suspend fun fetchDigitalMonsters(eggId: Int? = null, monsterId: Int? = null, battleStage: Int? = null, getEggs: Boolean? = null): List<DigitalMonster>? {
        return withContext(Dispatchers.IO) {
            val api = RetrofitInstance.getApi(context)
            val response = api.fetchDigitalMonsters(eggId, monsterId, battleStage, getEggs)
            if (response.isSuccessful) {
                response.body()?.digitalMonsters
            } else {
                Log.e("FetchService", "Failed to fetch Digital Monsters. Response code: ${response.code()}")
                null
            }
        }
    }


    //suspend fun fetchDigitalMonsters(eggId: Int? = 0, monsterId: Int? = 0): DigitalMonster? {
    //    return withContext(Dispatchers.IO) {
    //        val api = RetrofitInstance.getApi(context)
    //        val response = api.fetchDigitalMonster(eggId ?: 0, monsterId ?: 0)
    //        if (response.isSuccessful) {
    //            response.body()?.digitalMonster
    //        } else {
    //            Log.e("FetchService", "Failed to fetch Digital Monster. Response code: ${response.code()}")
    //            null
    //        }
    //    }
    //}
}
