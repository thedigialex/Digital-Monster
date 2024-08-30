package thedigialex.digitalpet.controller

import android.content.Context
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.launch
import thedigialex.digitalpet.model.entities.DigitalMonster
import thedigialex.digitalpet.model.entities.User
import thedigialex.digitalpet.services.FetchService

class UserController(private val context: Context, private val scope: CoroutineScope) {
    private var fetchService: FetchService = FetchService(context)
    private val user: User

    init {
        user = getUserData()
        getUserInventory()
    }

    private fun getUserData(): User {
        val sharedPreferences = context.getSharedPreferences("UserData", Context.MODE_PRIVATE)
        return User(
            id = sharedPreferences.getLong("userId", -1),
            name = sharedPreferences.getString("userName", "")!!,
            email = sharedPreferences.getString("userEmail", "")!!,
            bits = sharedPreferences.getInt("userBits", 0),
        )
    }

    fun getUserDigitalMonster(callback: (DigitalMonster?) -> Unit) {
        scope.launch {
            val mainMonster = fetchService.fetchUserDigitalMonsters()
            if (mainMonster != null) {
                mainMonster.digital_monster.setupSprite(context) {

                    callback(mainMonster.digital_monster)
                }
                user.mainDigitalMonster = mainMonster
            } else {
                callback(null)
            }
        }
    }

    fun getDigitalMonsters(eggId: Int? = null, monsterId: Int? = null, battleStage: Int? = null, eggReturn: Boolean? = null, callback: (List<DigitalMonster>?) -> Unit) {
        scope.launch {
            val digitalMonsters = fetchService.fetchDigitalMonsters(eggId, monsterId, battleStage, eggReturn)
            if (digitalMonsters != null) {
                var spritesRemaining = digitalMonsters.size
                digitalMonsters.forEach { monster ->
                    monster.setupSprite(context) {
                        spritesRemaining--
                        if (spritesRemaining == 0) {
                            callback(digitalMonsters)
                        }
                    }
                }
            } else {
                callback(null)
            }
        }
    }

    private fun getUserInventory() {
        scope.launch {
            user.inventory = fetchService.fetchUserInventory()
        }
    }
}
