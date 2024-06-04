package thedigialex.digitalpet.services

import android.content.Context
import android.util.Log
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.withContext
import thedigialex.digitalpet.model.entities.UserDigitalMonster
import thedigialex.digitalpet.model.responses.UserDigitalMonsterResponse
import thedigialex.digitalpet.network.RetrofitInstance

class FetchService(private val context: Context) {

    suspend fun fetchUserDigitalMonsters(): List<UserDigitalMonster> {
        return withContext(Dispatchers.IO) {
            val api = RetrofitInstance.getApi(context)
            val response = api.getAllUserDigitalMonsters()
            if (response.isSuccessful) {
                val responseBody = response.body()
                Log.d("FetchService", "Response Body: $responseBody")
                responseBody?.let { userDigitalMonsterResponse ->
                    val userDigitalMonsterList = userDigitalMonsterResponse.userDigitalMonsters
                    userDigitalMonsterList ?: emptyList()
                } ?: emptyList()
            } else {
                Log.e("FetchService", "Failed to fetch digital monsters. Response code: ${response.code()}")
                emptyList()
            }
        }
    }
}
