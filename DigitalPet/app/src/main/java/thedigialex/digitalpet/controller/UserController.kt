package thedigialex.digitalpet.controller

import android.content.Context
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.launch
import thedigialex.digitalpet.model.entities.DigitalMonster
import thedigialex.digitalpet.model.entities.User
import thedigialex.digitalpet.services.FetchService

class UserController(private val context: Context, private val scope: CoroutineScope, private var user: User) {
    private var fetchService: FetchService = FetchService(context)

    init {
        getUserInventory()
    }

    fun getUserDigitalMonster(callback: () -> Unit) {
        scope.launch {
            val mainMonster = fetchService.fetchUserDigitalMonster()
            if (mainMonster != null) {
                mainMonster.digital_monster.setupSprite(context) {
                    user.mainDigitalMonster = mainMonster
                    callback()
                }
            } else {
                callback()
            }
        }
    }

    fun evolveUserDigitalMonster(callback: () -> Unit) {
        scope.launch {
            val mainMonster = fetchService.evolveUserDigitalMonster()
            if (mainMonster != null) {
                mainMonster.digital_monster.setupSprite(context) {
                    user.mainDigitalMonster = mainMonster
                    callback()
                }
            } else {
                callback()
            }
        }
    }

    fun updateUserDigitalMonster() {
        scope.launch {
            fetchService.postUserDigitalMonster(user.mainDigitalMonster!!)
        }
    }

    private fun getUserInventory() {
        scope.launch {
            user.inventory = fetchService.fetchUserInventory()
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
}
