package thedigialex.digitalpet.api

import android.content.Context
import android.util.Log
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext
import thedigialex.digitalpet.model.entities.DigitalMonster
import thedigialex.digitalpet.model.entities.InventoryItem
import thedigialex.digitalpet.model.entities.User
import thedigialex.digitalpet.model.entities.UserDigitalMonster
import thedigialex.digitalpet.model.entities.UserTrainingEquipment
import thedigialex.digitalpet.util.DataManager

class FetchService(private val context: Context, private val showLoading: (Boolean) -> Unit) {

    fun checkToken(): Boolean {
        val token = DataManager.getToken(context)
        return token != null
    }

    private fun performAuthAction(isLoading: Boolean, action: suspend () -> Unit) {
        showLoading(isLoading)
        CoroutineScope(Dispatchers.IO).launch {
            try {
                action()
            } catch (e: Exception) {
                Log.e("network error", "An error occurred: ${e.message}")
            }
            finally {
                withContext(Dispatchers.Main) {
                    showLoading(false)
                }
            }
        }
    }

    fun performLogin(email: String, password: String, onLoginSuccess: () -> Unit, onLoginFailure: (String) -> Unit) {
        performAuthAction(true) {
            val response = ApiClient.getApi(context).login(email, password)
            if (response.isSuccessful && response.body()?.status == true) {
                response.body()?.let { loginResponse ->
                    DataManager.saveToken(context, loginResponse.token)
                    DataManager.saveUser(context, loginResponse.user)
                    withContext(Dispatchers.Main) {
                        onLoginSuccess()
                    }
                }
            } else {
                withContext(Dispatchers.Main) {
                    onLoginFailure("Login Failed")
                }
            }
        }
    }

    fun performRegistration(name: String, email: String, password: String, onLoginSuccess: () -> Unit, onLoginFailure: (String) -> Unit)  = performAuthAction(true) {
       val response = ApiClient.getApi(context).registerUser(name, email, password)
       if (response.isSuccessful && response.body()?.status == true) {
           response.body()?.let { loginResponse ->
               DataManager.saveToken(context, loginResponse.token)
               DataManager.saveUser(context, loginResponse.user)
               withContext(Dispatchers.Main) {
                   onLoginSuccess()
               }
           }
       } else {
           withContext(Dispatchers.Main) {
               onLoginFailure("Registration Failed")
           }
       }
   }

    fun validateToken(onLoginSuccess: () -> Unit, onLoginFailure: (String) -> Unit) = performAuthAction(true) {
        val response = ApiClient.getApi(context).checkToken()
        if (response.isSuccessful && response.body()?.status == true) {
            response.body()?.let { loginResponse ->
                DataManager.saveUser(context, loginResponse.user)
                withContext(Dispatchers.Main) {
                    onLoginSuccess()
                }
            }
        } else {
            withContext(Dispatchers.Main) {
                onLoginFailure("Token invalid")
            }
        }
    }

    fun getEggs(onEggsFetched: (List<DigitalMonster>) -> Unit) {
        performAuthAction(true) {
            val response = ApiClient.getApi(context).getEggs()
            if (response.isSuccessful) {
                response.body()?.let { digitalMonstersResponse ->
                    val eggs = digitalMonstersResponse.digitalMonsters

                    val eggsCount = eggs.size
                    var readySpritesCount = 0
                    fun checkAllSpritesReady() {
                        if (readySpritesCount == eggsCount) {
                            CoroutineScope(Dispatchers.Main).launch {
                                onEggsFetched(eggs)
                            }
                        }
                    }
                    eggs.forEach { egg ->
                        egg.setupSprite(context, "Data") {
                            readySpritesCount++
                            checkAllSpritesReady()
                        }
                    }
                }
            }
        }
    }

    fun fetchAndAttachItemsForSale(user: User, itemType: String, context: Context, onComplete: () -> Unit) {
        performAuthAction(false) {
            val response = ApiClient.getApi(context).getItems(itemType)
            if (response.isSuccessful) {
                response.body()?.let { itemResponse ->
                    val items = itemResponse.items
                    if (items.isEmpty()) {
                        user.itemsForSale = null
                        onComplete()
                    }
                    else{
                        val itemCount = items.size
                        var readySpritesCount = 0
                        fun checkAllSpritesReady() {
                            if (readySpritesCount == itemCount) {
                                CoroutineScope(Dispatchers.Main).launch {
                                    user.itemsForSale = items
                                    onComplete()
                                }
                            }
                        }
                        items.forEach { item ->
                            item.setupSprite(context) {
                                readySpritesCount++
                                checkAllSpritesReady()
                            }
                        }
                    }
                }
            }
        }
    }

    fun saveUserDigitalMonster(userDigitalMonster: UserDigitalMonster) {
        performAuthAction(true) {
            ApiClient.getApi(context).saveUserDigitalMonster(
                id = userDigitalMonster.id,
                name = userDigitalMonster.name,
                level = userDigitalMonster.level,
                exp = userDigitalMonster.exp,
                strength = userDigitalMonster.strength,
                agility = userDigitalMonster.agility,
                defense = userDigitalMonster.defense,
                mind = userDigitalMonster.mind,
                hunger = userDigitalMonster.hunger,
                exercise = userDigitalMonster.exercise,
                clean = userDigitalMonster.clean,
                energy = userDigitalMonster.energy,
                maxEnergy = userDigitalMonster.maxEnergy,
                wins = userDigitalMonster.wins,
                losses = userDigitalMonster.losses,
                trainings = userDigitalMonster.trainings,
                maxTrainings = userDigitalMonster.maxTrainings,
                currentEvoPoints = userDigitalMonster.currentEvoPoints,
                sleepStartedAt = userDigitalMonster.sleepStartedAt)
        }
    }

    fun createNewUserDigitalMonster(digitalMonsterId: Int, name: String, onSuccess: (UserDigitalMonster?) -> Unit) {
        performAuthAction(true) {
            val response = ApiClient.getApi(context).createUserDigitalMonster(digitalMonsterId, name)
            if (response.isSuccessful && response.body()?.status == true) {
                response.body()?.userDigitalMonster?.let { userDigitalMonster ->
                    setupSpriteAndReturn(userDigitalMonster, "Data", onSuccess)
                }
            }
        }
    }

    fun updateInventoryItem(inventoryItem: InventoryItem) {
        performAuthAction(false) {
            ApiClient.getApi(context).updateInventoryItem(inventoryItem.id)
        }
    }

    fun setupSpriteAndReturnItem(inventoryItem: InventoryItem, onSuccess: (InventoryItem?) -> Unit) {
        performAuthAction(true) {
            inventoryItem.item.setupSprite(context) {
                onSuccess(inventoryItem)
            }
        }
    }

    fun setupSpriteAndReturnTrainingEquipment(userTrainingEquipment: UserTrainingEquipment, onSuccess: (UserTrainingEquipment?) -> Unit) {
        performAuthAction(true) {
            userTrainingEquipment.trainingEquipment.setupSprite(context) {
                onSuccess(userTrainingEquipment)
            }
        }
    }

    fun setUpSpriteImages(userDigitalMonster: UserDigitalMonster, onSuccess: (UserDigitalMonster?) -> Unit) {
        performAuthAction(true) {
            val spriteType = when (userDigitalMonster.digitalMonster.stage) {
                "Egg", "Fresh", "Child" -> "Data"
                else -> userDigitalMonster.type
            }
            setupSpriteAndReturn(userDigitalMonster, spriteType, onSuccess)
        }
    }

    private fun setupSpriteAndReturn(userDigitalMonster: UserDigitalMonster, type: String, onSuccess: (UserDigitalMonster?) -> Unit) {
        performAuthAction(true) {
            userDigitalMonster.digitalMonster.setupSprite(context, type) {
                onSuccess(userDigitalMonster)
            }
        }
    }

    fun evoUserDigitalMonster(onSuccess: (UserDigitalMonster?) -> Unit) {
        performAuthAction(true) {
            val response = ApiClient.getApi(context).evolve()
            if (response.isSuccessful && response.body()?.status == true) {
                response.body()?.userDigitalMonster?.let { userDigitalMonster ->
                    val spriteType = when (userDigitalMonster.digitalMonster.stage) {
                        "Egg", "Fresh", "Child" -> "Data"
                        else -> userDigitalMonster.type
                    }
                    setupSpriteAndReturn(userDigitalMonster, spriteType, onSuccess)
                }
            }
        }
    }
}