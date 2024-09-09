package thedigialex.digitalpet.services

import android.content.Context
import android.util.Log
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.withContext
import thedigialex.digitalpet.model.entities.DigitalMonster
import thedigialex.digitalpet.model.entities.Inventory
import thedigialex.digitalpet.model.entities.Item
import thedigialex.digitalpet.model.entities.UserDigitalMonster
import thedigialex.digitalpet.network.RetrofitInstance

class FetchService(private val context: Context) {
    suspend fun createUserDigitalMonster(eggId: Int): UserDigitalMonster? {
        return withContext(Dispatchers.IO) {
            val api = RetrofitInstance.getApi(context)
            val response = api.createUserDigitalMonster(eggId)
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

    suspend fun fetchUserDigitalMonster(): UserDigitalMonster? {
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

    suspend fun evolveUserDigitalMonster(): UserDigitalMonster? {
        return withContext(Dispatchers.IO) {
            val api = RetrofitInstance.getApi(context)
            val response = api.evolveDigitalMonster()
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

    suspend fun postUserDigitalMonster(userDigitalMonster: UserDigitalMonster): UserDigitalMonster? {
        return withContext(Dispatchers.IO) {
            val api = RetrofitInstance.getApi(context)
            val response = api.updateUserDigitalMonster(userDigitalMonster)
            if (response.isSuccessful) {
                val responseBody = response.body()
                Log.d("API", response.body().toString())
                responseBody?.let { userDigitalMonsterResponse ->
                    val updatedMonster = userDigitalMonsterResponse.userDigitalMonster
                    Log.d("UserDigitalMonster", "ID: ${updatedMonster.digitalMonsterId}, Name: ${updatedMonster.name}")
                    updatedMonster
                } ?: run {
                    Log.d("UserDigitalMonster", "No data received. Response body is null.")
                    null
                }
            } else {
                Log.e("FetchService", "Failed to update user digital monster. Response code: ${response.code()}")
                null
            }
        }
    }

    suspend fun fetchUserInventory(): List<Inventory> {
        return withContext(Dispatchers.IO) {
            val api = RetrofitInstance.getApi(context)
            val response = api.getUserInventory()
            if (response.isSuccessful) {
                response.body() ?: emptyList()
            } else {
                Log.e("FetchService", "Failed to fetch Inventory. Response code: ${response.code()}")
                emptyList()
            }
        }
    }

    suspend fun fetchItems(type: String? = null): List<Item> {
        return withContext(Dispatchers.IO) {
            val api = RetrofitInstance.getApi(context)
            val response = api.getItems(type)
            if (response.isSuccessful) {
                val responseBody = response.body()
                responseBody ?: emptyList()
            } else {
                Log.e("FetchService", "Failed to fetch items. Response code: ${response.code()}")
                emptyList()
            }
        }
    }

    suspend fun fetchDigitalMonsters(eggId: Int? = null, monsterId: Int? = null, battleStage: Int? = null, getEggs: Boolean? = null): List<DigitalMonster>? {
        return withContext(Dispatchers.IO) {
            val api = RetrofitInstance.getApi(context)
            val response = api.fetchDigitalMonsters(eggId, monsterId, battleStage, getEggs)
            if (response.isSuccessful) {
                response.body()
            } else {
                Log.e("FetchService", "Failed to fetch Digital Monsters. Response code: ${response.code()}")
                null
            }
        }
    }

}
